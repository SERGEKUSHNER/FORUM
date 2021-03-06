<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
        'csrfToken'=>csrf_token(),
        'user'=>Auth::user(),
        'signedIn'=> Auth::check()
        ]) !!};
    </script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.css">


    <style>
            body { padding-bottom: 100px;}
        .level{ display: flex; align-items: center;}
        .level-item {margin-right: 1em;}
        .flex {flex: 1;}
        .mr-1 {margin-right: 1em;}
        .ml-a {margin-left: auto;}
        [v-cloak] {display:none;}
        .ais-highlight > em { background: yellow; font-style: normal; }
    </style>

    @yield('header')
</head>
<body style="padding-bottom: 100px; ">
    <div id="app">

        @include ('layouts.nav')
        <main class="py-4">
            @yield('content')
            <flash message="{{session('flash')}}"></flash>
        </main>
    </div>
@yield('scripts')
</body>
</html>
