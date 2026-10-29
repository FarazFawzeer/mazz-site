{{-- Standalone on purpose: it must still show when the database or the layout is the thing that broke. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title ?? 'Something went wrong' }} · {{ config('mazz.name', 'Mazz Laptop Hub') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #0a0b0e; color: #f2f3f5;
               font-family: Nunito, "Segoe UI", Arial, sans-serif; text-align: center; }
        main { max-width: 460px; }
        img { height: 56px; width: auto; }
        h1 { margin: 24px 0 8px; font-size: 28px; }
        p { margin: 0 0 24px; color: #b3b8c1; line-height: 1.6; }
        a.wa { display: inline-flex; align-items: center; gap: 8px; height: 46px; padding: 0 22px; border-radius: 999px;
               background: rgba(37, 163, 90, .16); color: #5fd896; font-weight: 700; text-decoration: none; }
        a.wa:hover { background: #25a35a; color: #fff; }
    </style>
</head>
<body>
    <main>
        <img src="/images/logo.png" alt="{{ config('mazz.name', 'Mazz Laptop Hub') }}">
        <h1>{{ $title ?? 'Something went wrong' }}</h1>
        <p>{{ $message ?? 'Sorry, this page couldn\'t load right now. Please try again in a minute, or message us and we\'ll help straight away.' }}</p>
        <a class="wa" href="https://wa.me/{{ config('mazz.whatsapp', '94789999976') }}">Chat on WhatsApp</a>
    </main>
</body>
</html>
