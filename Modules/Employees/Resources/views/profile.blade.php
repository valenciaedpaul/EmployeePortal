@extends('master.layout')

@section('title')
    - Employee Profile
@endsection

@section('content')
    <div class="row">
        @include('employees::partials.user_profile_card')
    </div>
@stop
