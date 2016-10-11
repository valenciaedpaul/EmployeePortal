{!! csrf_field() !!}
<table class="highlight" id="employee_applications_table" style="width: 100% !important;">
    <thead>
        <tr>
            <th name="id">ID</th>
            <th name="application_type">Application Type</th>
            <th name="status">Status</th>
            <th name="date_filed">Filed (yyyy-mm-dd)</th>
            <th name="action">Action</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td colspan="4" style="text-align: center;"><i>No records available</i></td>
        </tr>
    </tbody>

    @include('applications::partials.view_application_modal')
</table>