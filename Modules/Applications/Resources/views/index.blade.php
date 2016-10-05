@extends('master.layout')

@section('title')
    - Applications
@endsection

@section('nav_buttons')
    <li><a href="{{ url('applications/form') }}">Apply</a></li>
@endsection

@section('content')
    <h2>Applications</h2>

    {!! csrf_field() !!}
    <input type="hidden" id="employee_id" value="{{ Auth::user()->id }}">
    <input type="hidden" id="access_level" value="{{ $access_level }}">

    <table class="highlight" id="applications_table">
        <thead>
        <tr>
            <th name="id">ID</th>
            <th name="employee_name">Employee Name</th>
            <th name="application_type">Application Type</th>
            <th name="status">Status</th>
            <th name="date_from">From</th>
            <th name="date_to">To</th>
            <th name="overtime_hours">Overtime Hours</th>
            <th name="supervisor_name">Supervisor</th>
            <th name="action">Action</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td colspan="9" style="text-align: center;"><i>No records available</i></td>
        </tr>
        </tbody>
    </table>

    @include('applications::partials.view_application_modal')
@stop

@section('scripts')
    <script type="text/javascript" src="/modules/Applications/Assets/js/applications.js"></script>
@endsection