@extends('layouts.app')

@section('content')
    <div id="app">
        <navigation></navigation>
        <notifications></notifications>
        <router-view></router-view>
        <portal-target name="modal"></portal-target>
    </div>
@endsection