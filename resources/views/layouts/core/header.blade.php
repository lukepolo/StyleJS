<nav class="s-nav">
	<div class="left">
		<a href="/" class="logo">
			StyleJS
		</a>
	</div>
	<div class="right">
        <a href="{{ action('Auth\OauthController@link', 'github') }}" class="item">
            Login
        </a>
        <a href="{{ action('Auth\OauthController@link', 'github') }}" class="s-button s-button-green s-button-skinny margin-left-1">
            Register
        </a>
	</div>
</nav>
