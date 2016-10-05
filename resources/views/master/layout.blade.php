<!DOCTYPE html>
<html>
    <head>
        <title>Employee Portal @yield('title')</title>

        <!--Import Google Icon Font-->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/materialize/css/materialize.css') }}" media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!-- DataTables -->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/datatables/css/jquery.dataTables.css') }}"/>
        <link type="text/css" rel="stylesheet" href="{{ asset('public/datatables/css/dataTables.material.css') }}"/>
        <link type="text/css" rel="stylesheet" href="{{ asset('public/datatables/css/dataTables.fontAwesome.css') }}"/>

        <!-- FontAwesome -->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/font-awesome-4.6.3/css/font-awesome.min.css') }}"/>

        <!-- SweetAlert -->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/sweetalert-master/dist/sweetalert.css') }}"/>

        <!-- Custom -->
        <link type="text/css" rel="stylesheet" href="{{ asset('public/custom/css/generic.css') }}"/>

        @yield('additional_head')
    </head>
    <body>
        <div id="preloader-container">
            <div class="progress heroic-red darken-1" style="margin: 0 !important;">
                <div id="progress_bar" class="determinate white" style="width: 0"></div>
            </div>
        </div>
        <nav>
            <div class="nav-wrapper heroic-red">
                <a href="{{ url('/') }}" class="brand-logo">Employee Portal</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    @yield('nav_buttons')
                    @if(Auth::user())
                        <li><a href="{{ url('employees/logout') }}">Logout</a></li>
                    @endif
                </ul>
            </div>
        </nav>
        <div class="container">
            @if(session()->has('message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel blue darken-1 white-text">
                            <i class="material-icons">info</i>
                            {{ session()->pull('message') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('success_message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel green darken-1 white-text">
                            <i class="material-icons">done</i>
                            {{ session()->pull('success_message') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('error_message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel red darken-1 white-text">
                            <i class="material-icons">error</i>
                            {{ session()->pull('error_message') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('warning_message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel yellow darken-4 white-text">
                            <i class="material-icons">done</i>
                            {{ session()->pull('warning_message') }}
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="{{ asset('public/js/jquery-3.1.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/materialize/js/materialize.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/custom/js/generic.js') }}"></script>
        <!-- DataTables -->
        <script type="text/javascript" src="{{ asset('public/datatables/js/jquery.dataTables.min.js') }}"></script>
        <!-- SweetAlert -->
        <script type="text/javascript" src="{{ asset('public/sweetalert-master/dist/sweetalert.min.js') }}"></script>
        @yield('scripts')
    </body>
</html>
