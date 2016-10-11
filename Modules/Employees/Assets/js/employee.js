$(document).ready(function(){
    loadTable();
});

function deleteApplication(id){
    swal({
            title: "Are you sure?",
            text: "You will not be able to recover this application after deleting!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var url = '/applications/delete';

                $.ajax({
                    url: url,
                    xhr: xhrProgressListener,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: $('input[name=_token]').val(),
                        id: id
                    },
                    success: function(data){
                        if(data.errors){
                            showErrorToast(data.errors);
                        }else{
                            $('#view_application_modal').closeModal();
                            showSuccessToast('Application Deleted');
                        }
                    },
                    error: function(data){
                        showErrorToast(data.errors)
                    },
                    complete: function(){
                        applications_table.ajax.reload();
                    }
                });
            } else {
                swal("Cancelled", "Application is safe :)", "error");
            }
        });
}

function loadTable(){
    var employee_id = $('#employee_id').val();

    var url = '/employees/profile/applications/compact/' + employee_id;

    applications_table = $('#employee_applications_table').DataTable({
        serverSide: true,
        bFilter: false,
        sDom: 'rt',
        ajax: {
            url: url,
            xhr: xhrProgressListener
        },
        order: [[ 0, 'desc']],
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: 'application_type', name: 'application_type' },
            { data: 'status', name: 'status' },
            { data: 'date_filed', name: 'date_filed' },
            {data: 'action', name: 'action', orderable: false, searchable: false, width: '20%'}
        ],
        "fnInitComplete": function(oSettings, json) {
            $('select').material_select();

            $(document).on('click', '.btn-delete_application', function(){
                deleteApplication($(this).data('id'))
            });
        }
    });

    $(document).on('click', '.btn-view_application', function(){
        var applicationID = $(this).data('id');
        var url = '/applications/view/' + applicationID;

        $.ajax({
            url: url,
            xhr: xhrProgressListener,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.application){
                    Object.keys(data.application).forEach(function(key){
                        if(key == 'status'){
                            if(data.application['status'] == 'approved'){
                                $('#status-badge-container').html('<span class="badge green darken-1 white-text"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Application Approved</span>');
                            }else if(data.application['status'] == 'pending'){
                                $('#status-badge-container').html('<span class="badge grey lighten-2"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Pending for Approval</span>');
                            }else if(data.application['status'] == 'disapproved' || data.application['status'] == 'denied'){
                                $('#status-badge-container').html('<span class="badge red darken-1 white-text"><i class="fa fa-ban" aria-hidden="true"></i> Application Denied</span>');
                            }
                        }else{
                            $('#'+key).val(data.application[key])
                        }
                    });

                    $('#view_application_modal').openModal({
                        dismissible: true, // Modal can be dismissed by clicking outside of the modal
                        opacity: .5, // Opacity of modal background
                        in_duration: 300, // Transition in duration
                        out_duration: 200, // Transition out duration
                        ready: function() {
                            $('.btn-approve_application_modal').data('id', data.application.application_id);
                            $('.btn-deny_application_modal').data('id', data.application.application_id);
                        },
                        complete: function() {
                            $('.btn-approve_application_modal').data('id', 0);
                            $('.btn-deny_application_modal').data('id', 0);
                            document.getElementById('application_form_view').reset();
                        }
                    });
                }
            },
            error: function(data){
            }
        });
    });
}