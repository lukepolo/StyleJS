<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('layouts.head')

        @stack('styles')

        <!-- Styles -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('layouts.core.header')

        @include('layouts.core.errors')
        @include('layouts.core.success')

        @yield('content')

        @include('layouts.core.footer')
    </body>
</html>
