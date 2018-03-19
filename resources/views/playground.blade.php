@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" crossorigin href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/codemirror.css">
    <link rel="stylesheet" crossorigin href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/theme/neat.css">
@endpush

@section('content')
    @include('landing.partials.playground', [
        'options' => 'show',
    ])
@endsection
