@extends('master.layout')

@section('title')
    - Login
@endsection

@section('content')
    <div id="port_content">

        {{--login container--}}
        <div class="row" id="login_container">
            <div class="container">
                <div class="col s12">
                    <div class="card" style="width: 70%; margin: auto">
                        <div class="card-content">
                            <span class="card-title">Login</span>
                            <div class="row">
                                <form id="login_form" action="{{ url('employees/login') }}" method="POST" class="col s12">
                                    {!! csrf_field() !!}
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="fa fa-user prefix"></i>
                                            <input type="email" name="email" value="{{ old('email') }}" id="email">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <i class="fa fa-key prefix"></i>
                                            <input type="password" name="password" id="password">
                                            <label for="password">Password</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if(Session::has('login_error'))
                            <div class="row">
                                <div class="container">
                                    <div class="col">
                                        <div class="card-panel red lighten-2 white-text">
                                            {{ Session::get('login_error') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="row">
                                <div class="container">
                                    <div class="col">
                                        <div class="card-panel red darken-1 white-text">
                                            Oops! Unable to submit request. Please make sure to deal with the following:
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>- {{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card-action center-align">
                            <button type="submit" form="login_form" class="waves-effect waves-light btn">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection