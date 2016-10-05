<!-- Modal Structure -->
<div id="view_application_modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Application Details <span id="status-badge-container"></span></h4>

        <div class="row" style="margin-top: 20px;">
            <form id="application_form_view" name="application_form_view" class="col s12">
                <input type="hidden" name="application_id" id="application_id">
                <input type="hidden" name="employee_id" id="employee_id">
                <div class="row">
                    <div class="input-field col s4">
                        <input placeholder="First Name" id="first_name" name="first_name" type="text" readonly>
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="input-field col s4">
                        <input placeholder="Middle Name" id="middle_name" type="text" name="middle_name" readonly>
                        <label for="middle_name">Middle Name</label>
                    </div>
                    <div class="input-field col s4">
                        <input placeholder="Last Name" id="last_name" type="text" name="last_name" readonly>
                        <label for="last_name">Last Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <input placeholder="Application Type" id="application_type" type="text" name="application_type" readonly>
                        <label for="application_type">Application Type</label>
                    </div>
                    <div class="input-field col s4">
                        <input placeholder="From (Date)" id="date_from" type="text" name="date_from" readonly>
                        <label for="date_from">From (Date)</label>
                    </div>
                    <div class="input-field col s4">
                        <input placeholder="To (Date)" id="date_to" type="text" name="date_to" readonly>
                        <label for="date_to">To (Date)</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <input placeholder="Number of days" id="number_of_days" type="text" name="number_of_days" readonly>
                        <label for="number_of_days">Number of days (for Leave applications)</label>
                    </div>
                    <div class="input-field col s4">
                        <input placeholder="Total OT Hours" id="overtime_hours" type="text" name="overtime_hours" readonly>
                        <label for="overtime_hours">Total OT Hours (Overtime only)</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <input placeholder="Department" id="department" type="text" name="department" readonly>
                        <label for="department">Department</label>
                    </div>
                    <div class="input-field col s4">
                        <input placeholder="Direct Supervisor" id="supervisor_name" type="text" name="supervisor_name" readonly>
                        <label for="supervisor_name">Direct Supervisor</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s8">
                        <textarea placeholder="Reason" id="reason" name="reason" class="materialize-textarea" readonly></textarea>
                        <label for="reason">Reason</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" id="btn-close-modal" data-modal_id="#view_application" class="modal-action action-btn modal-close waves-effect btn btn-flat">Close</a>
        @if(\Modules\Employees\Entities\Employee::canApprove(Auth::user()->id))
            <button class="btn btn-small action-btn waves-effect waves-light red darken-1 btn-deny_application_modal" type="button" title="Deny" data-id="">
                <i class="fa fa-thumbs-down" aria-hidden="true"></i> Deny
            </button>
            <button class="btn btn-small action-btn waves-effect waves-light green darken-1 btn-approve_application_modal" type="button" title="Approve" data-id="">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
            </button>
        @endif
    </div>
</div>