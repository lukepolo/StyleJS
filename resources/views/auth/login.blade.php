@extends('layouts.public')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="align-center margin-bottom-3">
					<h1>
						Sign in
					</h1>

					<p>
						Welcome Back
					</p>
				</div>


				<div class="panel panel-default">
					<div class="panel-body">
						<h4 class="dotted-title">
							<span>
								Sign in with
							</span>
						</h4>

						<div class="align-center">
							<a href="{{ action('Auth\OauthController@link', 'github') }}"
							class="s-button-flat s-button-blue-flat">
								<i class="s-github"></i>
								Github
							</a>
						</div>
					</div>
				</div>

				<div class="align-center margin-top-3">
					Not already registered? <a href="/register">Wait no more!</a>
				</div>
			</div>
		</div>
	</div>
@endsection
