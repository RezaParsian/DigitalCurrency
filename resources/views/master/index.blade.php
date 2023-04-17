<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="container mx-auto p-2 md:p-0" id="app">
    @yield('content')
</div>
@vite('resources/js/app.js')

@yield('script')
</body>
</html>
