$(function () {
    /*
     * Admins Page
     */
    // Datatable
    $('#admin_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
        'order'       : [[ 1, "desc" ]]
    });
        
    /*
     * Users Page
     */
    // Datatable
    $('#user_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
        'order'       : [[ 2, "desc" ]]
    });
        
    /*
     * Star Review
     */
    $('.kv-gly-star').rating({
        containerClass: 'is-star',
        defaultCaption: '{rating}',
        starCaptions: function (rating) {
            return rating;
        },
        showClear: false,
        displayOnly: true,
    });

    /*
     * App Reviews Page
     */

    // Datatable
    $('#appreviews_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'scrollY'     : true,
        'columnDefs': [
            { width: 220, targets: 2 }
        ],
        'autoWidth'   : true,
        'order'       : [[ 4, "desc" ]]
    });
    
    /*
     * Non DogFriendlys Page
     */
    // Datatable
    $('#nondogfriendlys_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'scrollY'     : true,
        'columnDefs': [
            { width: 220, targets: 2 }
        ],
        'autoWidth'   : true,
        'order'       : [[ 3, "desc" ]]
    });
    
    /*
     * App Review Page
     */

    // Datatable
    $('#appreview_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'scrollY'     : true,
        'columnDefs': [
            { width: 220, targets: 2 }
        ],
        'autoWidth'   : true,
        'order'       : [[ 4, "desc" ]]
    });

    /*
     * Non DogFriendly Page
     */
    // Datatable
    $('#nondogfriendly_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'scrollY'     : true,
        'columnDefs': [
            { width: 220, targets: 2 }
        ],
        'autoWidth'   : true,
        'order'       : [[ 3, "desc" ]]
    });
    
    /*
     * New Locations Page
     */
    // Datatable
    $('#newlocations_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'scrollY'     : true,
        'columnDefs': [
            { width: 220, targets: 2 }
        ],
        'autoWidth'   : true,
        'order'       : [[ 5, "desc" ]]
    });
    
    /*
     * New Location Page
     */
    // Datatable
    $('#newlocation_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'scrollY'     : true,
        'columnDefs': [
            { width: 220, targets: 2 }
        ],
        'autoWidth'   : true,
        'order'       : [[ 3, "desc" ]]
    });
});

/*
 * User Management
*/
var user_id = 0;

function viewActionUserModal(url, action, id) {
    user_id = id;
    $('#' + url + '-' + action + '-modal').modal('show');
}

function actionUser(url, action) {
    $.ajax({
        type: 'GET',
        url: "../users/" + action + "/" + user_id,
        success: function(resposne) {
            var data = JSON.parse(resposne);
            console.log(data);
            if ( data.status == 'success' ) {
                window.location = $('#base_url').val() + url + 's';
            } else {
                setTimeout(function() {
                    $.bootstrapGrowl(data.message, {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
            }
            $('#' + url + '-' + action + '-modal').modal('hide');
        },
        error: function(data) {
            setTimeout(function() {
                $.bootstrapGrowl('Server Error', {
                    type: 'danger',
                    allow_dismiss: true
                });
            }, 1000);
            $('#' + url + '-' + action + '-modal').modal('hide');
        }
    });
}

/*
 * Report Management
*/
var report_place = report_address = '';
var report_id = 0;

function viewReport(url, place, address) {
    window.location = $('#base_url').val() + url + "/" + place + "/" + address;
}

function viewDeleteReportsModal(url, place, address) {
    report_place = place;
    report_address = address;
    $('#' + url + '-delete-modal').modal('show');
}

function deleteReports(url) {
    $.ajax({
        type: 'GET',
        url: "../" + url + "/delete/" + report_place + "/" + report_address,
        success: function(resposne) {
            var data = JSON.parse(resposne);
            console.log(data);
            if ( data.status == 'success' ) {
                window.location = $('#base_url').val() + url;
            } else {
                setTimeout(function() {
                    $.bootstrapGrowl(data.message, {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
            }
            $('#' + url + '-delete-modal').modal('hide');
        },
        error: function(data) {
            setTimeout(function() {
                $.bootstrapGrowl('Server Error', {
                    type: 'danger',
                    allow_dismiss: true
                });
            }, 1000);
            $('#' + url + '-delete-modal').modal('hide');
        }
    });
}

function viewDeleteReportModal(url, id) {
    report_id = id;
    $('#' + url + '-delete-modal').modal('show');
}

function deleteReport(url) {
    $.ajax({
        type: 'GET',
        url: "../../" + url + "/delete/" + report_id,
        success: function(resposne) {
            var data = JSON.parse(resposne);
            console.log(data);
            if ( data.status == 'success' ) {
                window.location = $('#base_url').val() + url + '/' + $('#report_place').val() + "/" + $('#report_address').val();
            } else {
                setTimeout(function() {
                    $.bootstrapGrowl(data.message, {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
            }
            $('#' + url + '-delete-modal').modal('hide');
        },
        error: function(data) {
            setTimeout(function() {
                $.bootstrapGrowl('Server Error', {
                    type: 'danger',
                    allow_dismiss: true
                });
            }, 1000);
            $('#' + url + '-delete-modal').modal('hide');
        }
    });
}