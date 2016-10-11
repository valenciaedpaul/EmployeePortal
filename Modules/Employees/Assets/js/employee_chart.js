var colors = [
    '#e53935',  // red darken-1 for USED
    '#4caf50'   // green for AVAILABLE
];

$(document).ready(function(){
    var employee_id = $('#employee_id').val();

    $.ajax({
        url: '/employees/profile/getTotalPaidLeaveDetails/' + employee_id,
        xhr: xhrProgressListener,
        type: 'GET',
        dataType: 'json',
        success: function(data){
            if(!data.errors){
                new Morris.Donut({
                    // ID of the element in which to draw the chart.
                    element: 'paid_leaves_chart',
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.
                    data: [
                        {label: "Used", value: data.used_paid},
                        {label: "Available", value: (data.paid_leave_credits - data.used_paid)}
                    ],
                    resize: true,
                    colors: colors
                });

                new Morris.Donut({
                    // ID of the element in which to draw the chart.
                    element: 'unpaid_leaves_chart',
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.
                    data: [
                        {label: "Used", value: data.used_unpaid},
                        {label: "Available", value: (data.unpaid_leave_credits - data.used_unpaid)}
                    ],
                    resize: true,
                    colors: colors
                });
            }
        },
        error: function(){

        }
    });
});