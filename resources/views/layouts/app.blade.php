<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Cloud - @yield('title')</title>
    <link rel="stylesheet" href="{{ secure_asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@700&family=Noto+Naskh+Arabic:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <main class="container">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ secure_asset('js/app.js') }}"></script>
</body>
</html>
