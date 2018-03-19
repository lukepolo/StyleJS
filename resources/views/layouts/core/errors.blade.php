<div class="container">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul style="list-style: none; padding: 0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>