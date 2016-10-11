@section('additional_head')
    <link rel="stylesheet" href="{{ asset('public/morris.js-0.5.1/morris.css') }}">
@endsection

<input type="hidden" id="employee_id" value="{{ $employee->id }}">
<input type="hidden" id="access_level" value="{{ $access_level }}">
<div class="col s12 m12">
    <div class="card center-card hoverable ">
        <div class="card-image">
            <img src="{{ asset('public/images/material_001.jpg') }}">
                <span class="card-title ep media-lg">
                    <img src="{{ $avatar }}" alt="avatar">
                    <span class="title"><strong>{{ $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name }}</strong></span><br>
                    <span class="subtitle"><em>{{ $employee_type }}</em></span>
                </span>
        </div>
        <div class="card-content">
            <strong class="label">Email Address: </strong> <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a><br>
            <strong class="label">Department: </strong> {{ $department }}

            <ul class="collapsible popout" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header ep active"><i class="fa fa-pie-chart" aria-hidden="true"></i> Leave Statistics</div>
                    <div class="collapsible-body">
                        <div class="row">
                            <div class="container">
                                <div id="paid_leaves" class="col s12 m6">
                                    <h5>Paid Leaves</h5>
                                    <div id="canvas-holder" >
                                        <div id="paid_leaves_chart" style="height: 250px;"></div>
                                    </div>
                                </div>
                                <div id="unpaid_leaves" class="col s12 m6">
                                    <h5>Unpaid Leaves</h5>
                                    <div id="canvas-holder">
                                        <div id="unpaid_leaves_chart" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header ep"><i class="fa fa-list-alt" aria-hidden="true"></i> Applications</div>
                    <div class="collapsible-body">
                        @include('employees::partials.employee_application_list')
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript" src="{{ asset('public/morris.js-0.5.1/raphael-min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/morris.js-0.5.1/morris.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Modules/Employees/Assets/js/employee_chart.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Modules/Employees/Assets/js/employee.js') }}"></script>
@endsection