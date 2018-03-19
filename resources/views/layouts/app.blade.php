<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('layouts.head')

        <script>
            window.Laravel = <?php echo json_encode([
                'env' => config('app.env'),
                'csrfToken' => csrf_token(),
                'FILE_TYPES' => \App\Models\Repository::FIlE_TYPES,
                'PROSE_WRAP' =>\App\Models\Repository::PROSE_WRAP,
                'ARROW_PARENTS' =>\App\Models\Repository::ARROW_PARENTS,
                'PATCH_STATUSES' => \App\Models\RepositoryPatch::STATUSES,
                'TRAILING_COMMAS' =>\App\Models\Repository::TRAILING_COMMAS,
                'ANALYSIS_SETTINGS' =>\App\Models\Repository::ANALYSIS_SETTINGS,
                'user' => \Auth::user()
            ]); ?>
        </script>

        <!-- Styles -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    </head>
    <body>

        {{-- TODO should somehow move these into the app --}}
        @include('layouts.core.errors')
        @include('layouts.core.success')

        @yield('content')

        <!-- Scripts -->
        <script src="{{ mix('/js/manifest.js') }}"></script>
        <script src="{{ mix('/js/vendor.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
