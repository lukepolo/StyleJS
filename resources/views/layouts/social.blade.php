<?php
    $twitter_username = 'TeamStyleJS';
    $short_description = 'The Front-End Coding Style Service';
    $long_description = 'Spend your time writing actual working code instead of cleaning it. StyleJS makes your JavaScript code beautiful. Just push your ugly (but working) code and we make it pretty.';
?>

@section('head-title')
    <title>{{ config('app.name') }} - {{ $short_description }}</title>

    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ config('app.name') }} - {{ $short_description }}" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="{{ '@' . $twitter_username  }}" />
    <meta name="twitter:title" content="{{ config('app.name') }} - {{ $short_description }}" />

    <meta name="description" content="{{ $long_description }}"/>
    <meta property="og:description" content="{{ $long_description }}" />
    <meta name="twitter:description" content="{{ $long_description }}" />
    <meta property="og:image" content="{{ config('app.url') }}/imgs/stylejs-logo.png">
    <meta name="twitter:image" content="{{ config('app.url') }}/imgs/stylejs-logo.png" />

    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Organization",
            "url": "{{ config('app.url') }}",
            "name": "{{ config('app.name') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ config('app.url') }}/imgs/stylejs-logo.png",
                "width": "1500",
                "height": "1500"
            },
            "sameAs": [
                "https://twitter.com/{{ $twitter_username }}"
            ]
        }
    </script>
@show