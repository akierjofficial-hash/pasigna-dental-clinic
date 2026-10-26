<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
        if ($request->user()) {
            return redirect()->route('portal');
        }

        return $this->start($request, 'login');
    }

    public function link(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'user' || !$user->is_active) {
            abort(403);
        }

        if ($user->google_id) {
            return redirect()->route('profile.show', ['tab' => 'account'])
                ->with('success', 'Google is already connected to your account.');
        }

        return $this->start($request, 'link');
    }

    private function start(Request $request, string $mode)
    {
        if (!$this->configured()) {
            return $this->failure($mode, 'Google sign-in is not available right now. Please use your password or try again later.');
        }

        $request->session()->put('google.auth', [
            'mode' => $mode,
            'user_id' => $mode === 'link' ? $request->user()->id : null,
            'redirect' => $mode === 'login' ? $this->safeReturnPath($request->query('redirect'), $request) : null,
        ]);

        return $this->googleProvider()
            ->scopes(['openid', 'profile', 'email'])
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(Request $request)
    {
        $flow = $request->session()->pull('google.auth');
        $mode = is_array($flow) ? ($flow['mode'] ?? null) : null;
        if (!in_array($mode, ['login', 'link'], true)) {
            return $this->failure('login', 'Please start Google sign-in again from the patient login page.');
        }

        if ($mode === 'link' && (!$request->user() || $request->user()->role !== 'user'
            || !$request->user()->is_active || $request->user()->id !== ($flow['user_id'] ?? null))) {
            return $this->failure('login', 'Your session changed. Please sign in and connect Google again.');
        }
        if ($mode === 'login' && $request->user()) {
            return $this->failure('login', 'Your session changed. Please sign in again.');
        }
        if (!$this->configured()) {
            return $this->failure($mode, 'Google sign-in is not available right now. Please use your password or try again later.');
        }

        try {
            $googleUser = $this->googleProvider()->user();
        } catch (\Throwable $e) {
            report($e);
            return $this->failure($mode, 'Google sign-in could not be completed. Please try again.');
        }

        $googleId = trim((string) $googleUser->getId());
        $email = strtolower(trim((string) $googleUser->getEmail()));
        $rawGoogleUser = (array) ($googleUser->user ?? []);
        if ($googleId === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || !array_key_exists('email_verified', $rawGoogleUser)
            || filter_var($rawGoogleUser['email_verified'], FILTER_VALIDATE_BOOLEAN) !== true) {
            return $this->failure($mode, 'Google did not provide a verified email address. Please choose another Google account.');
        }

        if ($mode === 'link') {
            return $this->completeLink($request->user(), $googleId, $email);
        }

        $user = User::withTrashed()->where('google_id', $googleId)->first();
        if ($user) {
            if ($user->trashed() || $user->role !== 'user' || !$user->is_active) {
                return $this->failure('login', 'This account cannot use patient Google sign-in. Please contact the clinic.');
            }
        } else {
            $emailAccount = User::withTrashed()->whereRaw('LOWER(email) = ?', [$email])->first();
            if ($emailAccount) {
                if ($emailAccount->trashed() || $emailAccount->role !== 'user' || !$emailAccount->is_active) {
                    return $this->failure('login', 'This account cannot use patient Google sign-in. Please contact the clinic.');
                }

                return $this->failure('login', $emailAccount->google_id
                    ? 'This email is already connected to another Google account.'
                    : 'An account already uses this email. Sign in with your password, then connect Google from your account settings.');
            }

            $user = User::create([
                'name' => $googleUser->getName() ?: 'User',
                'email' => $email,
                'google_id' => $googleId,
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
                'password_set' => false,
            ]);
        }

        Auth::login($user, true);
        $request->session()->forget('url.intended');

        $returnPath = $this->safeReturnPath($flow['redirect'] ?? null, $request);
        return redirect()->to($returnPath ?? route('public.services.index'));
    }

    private function completeLink(User $user, string $googleId, string $email)
    {
        if (strcasecmp($user->email, $email) !== 0) {
            return $this->failure('link', 'Use the Google account with the same email address as your patient account.');
        }

        $otherAccount = User::withTrashed()->where('google_id', $googleId)->first();
        if ($otherAccount && $otherAccount->id !== $user->id) {
            return $this->failure('link', 'This Google account is already connected to another account.');
        }
        if ($user->google_id && !hash_equals((string) $user->google_id, $googleId)) {
            return $this->failure('link', 'A different Google account is already connected to your patient account.');
        }

        $user->google_id = $googleId;
        $user->email_verified_at ??= now();
        $user->save();

        return redirect()->route('profile.show', ['tab' => 'account'])
            ->with('success', 'Google sign-in is now connected to your patient account.');
    }

    private function failure(string $mode, string $message)
    {
        $route = $mode === 'link' && Auth::user()?->role === 'user'
            ? route('profile.show', ['tab' => 'account'])
            : route('userlogin');

        return redirect()->to($route)->withErrors(['email' => $message]);
    }

    private function safeReturnPath(mixed $value, Request $request): ?string
    {
        if (!is_string($value) || $value === '' || strlen($value) > 2048
            || preg_match('/[\x00-\x1F\x7F\\\\]/', $value)) {
            return null;
        }

        $parts = parse_url($value);
        if ($parts === false || isset($parts['user']) || isset($parts['pass'])) {
            return null;
        }
        if (isset($parts['scheme']) || isset($parts['host'])) {
            if (!isset($parts['scheme'], $parts['host'])
                || strcasecmp($parts['scheme'], $request->getScheme()) !== 0
                || strcasecmp($parts['host'], $request->getHost()) !== 0
                || ($parts['port'] ?? ($parts['scheme'] === 'https' ? 443 : 80)) !== $request->getPort()) {
                return null;
            }
        } elseif (!str_starts_with($value, '/')) {
            return null;
        }

        $path = ($parts['path'] ?? '/')
            . (isset($parts['query']) ? '?'.$parts['query'] : '')
            . (isset($parts['fragment']) ? '#'.$parts['fragment'] : '');

        if (!str_starts_with($path, '/') || str_starts_with($path, '//')
            || preg_match('/%(?:2f|5c|0[0-9a-f]|1[0-9a-f]|7f)/i', $path)) {
            return null;
        }

        return $path;
    }

    private function configured(): bool
    {
        return (bool) (config('services.google.client_id')
            && config('services.google.client_secret')
            && config('services.google.redirect'));
    }

    private function googleProvider()
    {
        $provider = Socialite::driver('google');

        // Some local launchers inherit an intentionally unavailable proxy on port 9.
        // Let Google requests connect directly only in that specific local setup.
        if ($provider instanceof AbstractProvider && config('app.env') === 'local' && $this->hasUnavailableLocalProxy()) {
            $provider->setHttpClient(new Client(['proxy' => '']));
        }

        return $provider;
    }

    private function hasUnavailableLocalProxy(): bool
    {
        $proxy = getenv('HTTPS_PROXY') ?: getenv('https_proxy') ?: getenv('ALL_PROXY');
        if (!is_string($proxy) || $proxy === '') {
            return false;
        }

        $parts = parse_url($proxy);

        return is_array($parts)
            && in_array($parts['host'] ?? null, ['127.0.0.1', 'localhost'], true)
            && ($parts['port'] ?? null) === 9;
    }
}
