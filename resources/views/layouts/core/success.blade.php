<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(\Session::has('success'))
                <div class="alert alert-success">
                    {{ \Session::get('success') }}
                </div>
            @endif
        </div>
    </div>
</div>