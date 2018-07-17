<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Events Toronto') }}</title>
    <meta name="description" content="Events in Toronto from latest data">
    <meta name="keywords" content="events, event, toronto">
    <meta name="robots" content="index, follow" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href="/css/app.css?v=1.0.8" rel="stylesheet">
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="/js/app.js?v=1.1.7" defer></script>
</body>
</html>
