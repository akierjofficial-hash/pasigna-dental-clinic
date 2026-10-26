<?php

namespace Tests\Feature;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleSignInTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.google', [
            'client_id' => 'test-client-id',
            'client_secret' => 'test-client-secret',
            'redirect' => 'http://127.0.0.1:8001/auth/google/callback',
        ]);
    }

    private function googleUser(string $id, string $email, mixed $verified = true): void
    {
        $raw = ['id' => $id, 'name' => 'Test Patient', 'email' => $email];
        if ($verified !== null) {
            $raw['email_verified'] = $verified;
        }
        $googleUser = (new SocialiteUser())->map($raw)->setRaw($raw);
        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andReturn($googleUser);
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
    }

    private function completeGoogleCallback(string $mode = 'login', ?int $userId = null, ?string $redirect = null)
    {
        return $this->withSession(['google.auth' => [
            'mode' => $mode,
            'user_id' => $userId,
            'redirect' => $redirect,
        ]])->get(route('google.callback'));
    }

    private function mockGoogleRedirect(): void
    {
        $provider = Mockery::mock();
        $provider->shouldReceive('scopes')->once()->with(['openid', 'profile', 'email'])->andReturnSelf();
        $provider->shouldReceive('with')->once()->with(['prompt' => 'select_account'])->andReturnSelf();
        $provider->shouldReceive('redirect')->once()->andReturn(redirect('https://accounts.google.com/test'));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
    }

    public function test_local_google_provider_bypasses_an_unavailable_port_nine_proxy(): void
    {
        $previousProxy = getenv('HTTPS_PROXY');
        putenv('HTTPS_PROXY=http://127.0.0.1:9');
        config()->set('app.env', 'local');

        try {
            $response = $this->get(route('google.redirect'));
            $this->assertSame('accounts.google.com', parse_url($response->headers->get('Location'), PHP_URL_HOST));

            $provider = Socialite::driver('google');
            $property = new \ReflectionProperty($provider, 'httpClient');
            $client = $property->getValue($provider);
            $this->assertInstanceOf(Client::class, $client);
            $this->assertSame('', $client->getConfig('proxy'));
        } finally {
            $previousProxy === false ? putenv('HTTPS_PROXY') : putenv('HTTPS_PROXY='.$previousProxy);
        }
    }

    public function test_google_redirect_stores_only_a_same_site_return_path(): void
    {
        $this->mockGoogleRedirect();
        $returnUrl = route('public.services.index').'?category=care#results';
        $this->get(route('google.redirect', ['redirect' => $returnUrl]))
            ->assertRedirect('https://accounts.google.com/test')
            ->assertSessionHas('google.auth.redirect', '/services?category=care#results');

        $this->mockGoogleRedirect();
        $this->get(route('google.redirect', ['redirect' => 'https://other.example/steal']))
            ->assertSessionHas('google.auth', fn ($flow) => array_key_exists('redirect', $flow) && $flow['redirect'] === null);
    }

    public function test_google_link_action_is_available_only_to_signed_in_patients(): void
    {
        $this->get(route('google.link'))->assertRedirect(route('login'));

        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $this->actingAs($staff)->get(route('google.link'))->assertForbidden();

        $patient = User::factory()->create(['role' => 'user', 'is_active' => true, 'google_id' => null]);
        $this->actingAs($patient)->get(route('profile.show', ['tab' => 'account']))
            ->assertOk()->assertSee('Connect Google');
        $this->mockGoogleRedirect();
        $this->get(route('google.link'))
            ->assertRedirect('https://accounts.google.com/test')
            ->assertSessionHas('google.auth.user_id', $patient->id);
    }

    public function test_first_google_sign_in_creates_only_an_active_public_account(): void
    {
        $this->googleUser('google-new', 'new@example.test');

        $this->completeGoogleCallback()->assertRedirect(route('public.services.index'));

        $user = User::where('email', 'new@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('user', $user->role);
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->password_set);
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame('google-new', $user->google_id);
        $this->assertFalse(Hash::check('', $user->password));
    }

    public function test_returning_google_user_keeps_the_same_account_and_uses_safe_return_path(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true, 'google_id' => 'google-return']);
        $this->googleUser('google-return', $user->email);

        $this->completeGoogleCallback(redirect: '/services?category=cleaning#book')
            ->assertRedirect(url('/services?category=cleaning#book'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_existing_password_account_is_not_linked_by_email(): void
    {
        $user = User::factory()->create([
            'role' => 'user', 'is_active' => true, 'google_id' => null,
            'password_set' => true, 'email' => 'existing@example.test',
        ]);
        $this->googleUser('google-existing', $user->email);

        $this->completeGoogleCallback()->assertRedirect(route('userlogin'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertNull($user->fresh()->google_id);
    }

    public function test_signed_in_patient_can_connect_the_matching_verified_google_account(): void
    {
        $user = User::factory()->create([
            'role' => 'user', 'is_active' => true, 'google_id' => null,
            'password_set' => true, 'email' => 'link@example.test',
        ]);
        $this->actingAs($user);
        $this->googleUser('google-link', $user->email);

        $this->completeGoogleCallback('link', $user->id)
            ->assertRedirect(route('profile.show', ['tab' => 'account']))
            ->assertSessionHas('success');
        $this->assertSame('google-link', $user->fresh()->google_id);
    }

    public function test_link_requires_the_same_signed_in_patient_and_email(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => true, 'google_id' => null]);
        $this->actingAs($user);

        $this->completeGoogleCallback('link', $user->id + 1)->assertRedirect(route('userlogin'));
        $this->assertNull($user->fresh()->google_id);

        $this->googleUser('google-other-email', 'different@example.test');
        $this->completeGoogleCallback('link', $user->id)
            ->assertRedirect(route('profile.show', ['tab' => 'account']))
            ->assertSessionHasErrors('email');
        $this->assertNull($user->fresh()->google_id);
    }

    public function test_google_account_cannot_be_linked_to_two_patients(): void
    {
        User::factory()->create(['role' => 'user', 'is_active' => true, 'google_id' => 'google-used']);
        $user = User::factory()->create(['role' => 'user', 'is_active' => true, 'google_id' => null]);
        $this->actingAs($user);
        $this->googleUser('google-used', $user->email);

        $this->completeGoogleCallback('link', $user->id)
            ->assertRedirect(route('profile.show', ['tab' => 'account']))
            ->assertSessionHasErrors('email');
        $this->assertNull($user->fresh()->google_id);
    }

    public function test_inactive_account_is_rejected_by_google_id_or_email(): void
    {
        foreach ([true, false] as $matchedById) {
            $user = User::factory()->create(['role' => 'user', 'is_active' => false, 'google_id' => $matchedById ? 'google-inactive' : null]);
            $this->googleUser($matchedById ? 'google-inactive' : 'google-new-inactive', $user->email);

            $this->completeGoogleCallback()->assertRedirect(route('userlogin'))->assertSessionHasErrors('email');
            $this->assertGuest();
            $this->assertSame($matchedById ? 'google-inactive' : null, $user->fresh()->google_id);
        }
    }

    public function test_staff_and_admin_are_rejected_by_google_id_and_email(): void
    {
        foreach (['staff', 'admin'] as $role) {
            foreach ([true, false] as $matchedById) {
                $user = User::factory()->create([
                    'role' => $role, 'is_active' => true,
                    'google_id' => $matchedById ? "google-$role" : null,
                ]);
                $this->googleUser($matchedById ? "google-$role" : "google-other-$role", $user->email);

                $this->completeGoogleCallback()->assertRedirect(route('userlogin'))->assertSessionHasErrors('email');
                $this->assertGuest();
                $this->assertSame($role, $user->fresh()->role);
            }
        }
    }

    public function test_unverified_or_missing_verification_is_rejected(): void
    {
        foreach ([false, null] as $verified) {
            $this->googleUser('google-unverified', 'unverified@example.test', $verified);
            $this->completeGoogleCallback()->assertRedirect(route('userlogin'))->assertSessionHasErrors('email');
            $this->assertGuest();
        }
        $this->assertDatabaseCount('users', 0);
    }

    public function test_external_or_protocol_relative_return_url_falls_back_to_public_services(): void
    {
        foreach (['https://other.example/path', '//other.example/path', '/%2Fother.example/path'] as $unsafe) {
            $this->googleUser('google-safe', 'safe@example.test');
            $this->completeGoogleCallback(redirect: $unsafe)->assertRedirect(route('public.services.index'));
            auth()->logout();
        }
    }

    public function test_callback_failure_returns_to_patient_login_without_exposing_exception(): void
    {
        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andThrow(new \RuntimeException('private provider response'));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->completeGoogleCallback()->assertRedirect(route('userlogin'))
            ->assertSessionHasErrors('email', 'Google sign-in could not be completed. Please try again.');
    }
}
