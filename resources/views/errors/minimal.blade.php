<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>@yield('title') | {{ config('clinic.name') }}</title>
    <style>
        :root{color-scheme:light dark;font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
        body{min-height:100vh;display:grid;place-items:center;margin:0;padding:24px;box-sizing:border-box;background:#f8f4fb;color:#3c1e5e}
        main{width:min(100%,540px);padding:30px;border:1px solid #d1b4e2;border-radius:16px;background:#fff;box-shadow:0 12px 35px #3c1e5e12}
        .code{font-size:13px;font-weight:800;letter-spacing:.12em;color:#7b4da5}h1{margin:10px 0;font-size:28px;line-height:1.2}p{line-height:1.55;color:#62576b}
        a{display:inline-block;margin-top:8px;padding:10px 15px;border-radius:9px;background:#563682;color:#fff;text-decoration:none;font-weight:700}a:focus-visible{outline:3px solid #9d66b9;outline-offset:3px}
        @media(prefers-color-scheme:dark){body{background:#180f24;color:#f7f1fb}main{background:#291b38;border-color:#4e3a60}.code{color:#d1b4e2}p{color:#cbbbd8}}
    </style>
</head>
<body><main role="main"><div class="code">@yield('code')</div><h1>@yield('title')</h1><p>@yield('message')</p><p>@yield('next')</p><a href="{{ url('/') }}">Go to home</a></main></body>
</html>
