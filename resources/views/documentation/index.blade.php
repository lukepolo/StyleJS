@extends('layouts.public')

@section('head-title')
    <title>FAQ | StyleJS</title>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default margin-bottom-4">
                    <div class="panel-body padding-sides-2">
                        <h1 class="dotted-title">
                            <span class="title-font-size">
                                FAQ
                            </span>
                        </h1>

                        <h3>1. What is StyleJS?</h3>
                        <p>
                            StyleJS is a continuous integration tool that analyses each commit. StyleJS checks to make sure
                            that all code is consistent based on the settings you provide within your repository settings.
                        </p>

                        <h3>2. Can it do other file types besides JS? </h3>
                        <p>
                            Yes! Anything <a href="https://prettier.io" rel="nofollow" target="_blank">Prettier</a> can handle, StyleJS can handle. A list is below
                        </p>

                        <ul>
                            @foreach(\App\Models\Repository::FIlE_TYPES as $fileType)
                                <li>{{ $fileType }}</li>
                            @endforeach
                        </ul>

                        <h3>3. Automation Setups</h3>
                        <p>
                            We provide numerous types of automation:
                        </p>
                        <ul>
                            @foreach(\App\Models\Repository::ANALYSIS_SETTINGS as $setting => $label)
                                <li>{{ $label }}</li>
                            @endforeach
                        </ul>
                        <p>
                        We also allow you run analysis manually!
                        This can further expand your current CI setup as we provide you with a webhook.
                        </p>

                        <h3>4. Why not just use prettier myself?</h3>
                        <p>
                            While yes you can use prettier standalone, StyleJS allows you to focus on your code. Rather than setup pre-commit
                            checks or other processes that your devs could skip regardless of what you choose. They can't skip this!
                        </p>

                        <h3>5. Support</h3>
                        <p>
                           Checkout our repository on <a href="https://github.com/lukepolo/stylejs">GitHub</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
