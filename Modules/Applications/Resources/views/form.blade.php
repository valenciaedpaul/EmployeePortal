@extends('master.layout')

@section('title')
    - Application Form
@endsection

@section('content')
    <div class="row" id="user-form-container">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Leave/CWS/OT Application Form</span>
                    <div class="row" style="margin-top: 20px;">
                        <form id="application_form" name="application_form" action="{{ URL::to('applications/form') }}" method="POST" class="col s12">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" id="id" value="{{ @$application->id }}">
                            <input type="hidden" name="employee_id" id="employee_id" value="{{ @$employee->id }}">
                            <div class="row">
                                <div class="input-field col s4">
                                    <input id="first_name" name="first_name" type="text" value="{{@$employee->first_name}}" readonly>
                                    <label for="first_name">First Name</label>
                                </div>
                                <div class="input-field col s4">
                                    <input id="middle_name" type="text" name="middle_name" value="{{@$employee->middle_name}}" readonly>
                                    <label for="middle_name">Middle Name</label>
                                </div>
                                <div class="input-field col s4">
                                    <input id="last_name" type="text" name="last_name" value="{{@$employee->last_name}}" readonly>
                                    <label for="last_name">Last Name</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s4">
                                    <select id="application_type_id" name="application_type_id">
                                        <option value="" disabled {{ (!isset($application)) ? 'selected' : '' }}>Please choose one...</option>
                                        @if($application_types)
                                            @foreach($application_types as $application_type)
                                                <option value="{{ $application_type->id }}" title="{{ $application_type->description }}" {{ (isset($application)) ? (($application_type->id == $application->application_type_id) ? 'selected' : '') : '' }}>{{ $application_type->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="application_type_id">Application Type</label>
                                </div>
                                <div class="input-field col s4">
                                    <input id="date_from" type="date" class="datepicker" name="date_from" value="{{ isset($application->date_from) ? date('j F, Y', strtotime(@$application->date_from)) : '' }}" required>
                                    <label for="date_from">From (Date)</label>
                                </div>
                                <div class="input-field col s4">
                                    <input id="date_to" type="date" class="datepicker" name="date_to" value="{{ isset($application->date_to) ? date('j F, Y', strtotime(@$application->date_to)) : '' }}" required>
                                    <label for="date_to">From (Date)</label>
                                </div>
                            </div>
                            <div class="row">
                                {{--Number of days (only for leave applications)--}}
                                <div class="input-field col s4">
                                    <input id="number_of_days" type="text" name="number_of_days" value="{{ @$application->number_of_days }}" >
                                    <label for="number_of_days">Number of days (for Leave applications)</label>
                                </div>
                                {{--Number of days (only for leave applications)--}}
                                <div class="input-field col s4">
                                    <input id="overtime_hours" type="text" name="overtime_hours" value="{{ @$application->overtime_hours }}" >
                                    <label for="overtime_hours">Total OT Hours (Overtime only)</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s4">
                                    <input id="department" type="text" name="department" value="{{@$department->name}}" readonly>
                                    <label for="department">Department</label>
                                </div>
                                <div class="input-field col s4">
                                    <select id="supervisor_id" name="supervisor_id">
                                        <option value="" disabled {{ (!isset($application)) ? 'selected' : '' }}>Please choose one...</option>
                                        @if($supervisors)
                                            @foreach($supervisors as $s)
                                                <option value="{{ $s->id }}" {{ (isset($application)) ? (($s->id == $application->supervisor_id) ? 'selected' : '') : '' }}>{{ ucwords($s->first_name . ' ' . $s->middle_name . ' ' . $s->last_name) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="supervisor_id">Direct Supervisor</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s8">
                                    <textarea id="reason" name="reason" class="materialize-textarea">{{ $application->reason or '' }}</textarea>
                                    <label for="reason">Reason</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if($errors->any())
                    <div class="container">
                        <div class="row">
                            <div class="col s12">
                                <div class="card-panel red darken-1 white-text">
                                    Oops! Unable to submit application. Please make sure to deal with the following:
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
                    <a href="{{ url('applications') }}" class="waves-effect waves-dark btn white grey-text text-darken-3">Cancel</a>
                    <button class="btn waves-effect waves-light" type="submit" form="application_form">
                        <i class="fa fa-paper-plane right" aria-hidden="true"></i>
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection