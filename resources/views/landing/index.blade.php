@extends('layouts.public')

@section('content')

    @include('layouts.core.github')

    <div class="relative">
        <div class="landing-section-1">
            <h1 class="zoomIn animated">
                The Front-End Coding Style Service for GitHub
            </h1>

            <h2 class="bounceInDown animated">
                Spend your time writing actual working code instead of cleaning the old ones
            </h2>

            <a href="{{ action('Auth\OauthController@link', 'github') }}" class="margin-top-3 s-button s-button-blue s-button-fat">
                Create an account
            </a>
        </div>
    </div>

    @include('landing.partials.features')

@endsection
