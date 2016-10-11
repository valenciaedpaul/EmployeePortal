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
    <body class="{{ Request::is('employees/login') ? 'login-bg' : '' }}">
        @if(Auth::check())
            <div id="preloader-container">
                <div class="progress red darken-1" style="margin: 0 !important;">
                    <div id="progress_bar" class="determinate white" style="width: 0"></div>
                </div>
            </div>
            <ul id="slide-out" class="side-nav">
                <li>
                    <div class="userView">
                        <img class="background" src="{{ asset('public/images/material_001.jpg') }}">
                        <a href="{{ url('employees/profile/') }}"><img class="circle" src="{{ \Modules\Employees\Entities\Employee::getProfilePic() }}"></a>
                        <a href="{{ url('employees/profile/') }}"><span class="white-text name">{{ \Modules\Employees\Entities\Employee::getFullname() }}</span></a>
                        <a href="{{ url('employees/profile/') }}"><span class="white-text email">{{ Auth::user()->email }}</span></a>
                    </div>
                </li>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li>
                            <a class="collapsible-header waves-effect {{ Request::is('applications*') ? 'active' : '' }}">Applications<i class="fa fa-list-alt" aria-hidden="true"></i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="{{ url('applications/form') }}"><i class="fa fa-pencil" aria-hidden="true"></i>Apply</a></li>
                                    <li><a href="{{ url('applications') }}"><i class="fa fa-list-alt" aria-hidden="true"></i>List</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="{{ url('employees/logout') }}" class="collapsible-header waves-effect"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a></a>
                        </li>
                    </ul>
                </li>
                @if(\Modules\Employees\Entities\EmployeeType::hasTopAccess(Auth::user()->type_id))
                    <li><div class="divider"></div></li>
                    <li><a class="subheader">Admin Settings</a></li>
                    <li><a class="waves-effect" href="#!">Employee Management</a></li>
                    <li><a class="waves-effect" href="#!">Application Types</a></li>
                @endif
            </ul>
            <nav>
                <div class="nav-wrapper red darken-1">
                    <a href="javascript:void(0)" data-activates="slide-out" class="button-collapse"><i class="fa fa-bars" aria-hidden="true"></i></a>
                    <a href="javascript:void(0)" data-activates="slide-out" class="brand-logo button-collapse">Employee Portal</a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        @if(Auth::user())
                            <li><a href="{{ url('employees/profile/') }}">{{ ucwords(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</a></li>
                            <li><a href="{{ url('applications/form') }}">Apply</a></li>
                        @endif
                        @yield('nav_buttons')
                        @if(Auth::user())
                            <li><a href="{{ url('employees/logout') }}">Logout</a></li>
                        @endif
                    </ul>
                </div>
            </nav>
        @endif
        <div class="container">
            @if(session()->has('message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel blue darken-1 white-text">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            {{ session()->pull('message') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('success_message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel green darken-1 white-text">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            {{ session()->pull('success_message') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('error_message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel red darken-1 white-text">
                            <i class="fa fa-exclamation" aria-hidden="true"></i>
                            {{ session()->pull('error_message') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('warning_message'))
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel yellow darken-4 white-text">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
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
