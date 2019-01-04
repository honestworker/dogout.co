$(function () {
    /*
     * Admins Page
     */
    var admin_id = 0;

    // Datatable
    $('#admin_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
    });

    // Modal
    $('.admin-table .btn-success').click(function() {
        admin_id = $(this).parent().parent().data('id');
        $('#admin-active-modal').modal('show');
    });

    $('.admin-table .btn-warning').click(function() {
        admin_id = $(this).parent().parent().data('id');
        $('#admin-disable-modal').modal('show');
    });

    $('.admin-table .btn-danger').click(function() {
        admin_id = $(this).parent().parent().data('id');
        $('#admin-delete-modal').modal('show');
    });
    
    // Button
    $('.active-admin').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/active/" + admin_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'admins';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });

    $('.disable-admin').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/disable/" + admin_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'admins';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });

    $('.delete-admin').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/delete/" + admin_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'admins';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });
    
    /*
     * Drivers Page
     */
    var driver_id = 0;

    // Datatable
    $('#driver_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
    });

    // Modal
    $('.driver-table .btn-success').click(function() {
        driver_id = $(this).parent().parent().data('id');
        $('#driver-active-modal').modal('show');
    });

    $('.driver-table .btn-warning').click(function() {
        driver_id = $(this).parent().parent().data('id');
        $('#driver-disable-modal').modal('show');
    });

    $('.driver-table .btn-danger').click(function() {
        driver_id = $(this).parent().parent().data('id');
        $('#driver-delete-modal').modal('show');
    });
    
    // Button
    $('.active-driver').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/active/" + driver_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'drivers';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#driver-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-active-modal').modal('hide');
            }
        });
    });

    $('.disable-driver').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/disable/" + driver_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'drivers';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#driver-disable-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-disable-modal').modal('hide');
            }
        });
    });

    $('.delete-driver').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/delete/" + driver_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'drivers';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#driver-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#driver-delete-modal').modal('hide');
            }
        });
    });
    
    /*
     * Users Page
     */
    var user_id = 0;

    // Datatable
    $('#user_table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true,
        'scrollY'     : true,
    });

    // Modal
    $('.user-table .btn-success').click(function() {
        user_id = $(this).parent().parent().data('id');
        $('#user-active-modal').modal('show');
    });

    $('.user-table .btn-warning').click(function() {
        user_id = $(this).parent().parent().data('id');
        $('#user-disable-modal').modal('show');
    });

    $('.user-table .btn-danger').click(function() {
        user_id = $(this).parent().parent().data('id');
        $('#user-delete-modal').modal('show');
    });
    
    // Button
    $('.active-user').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/active/" + user_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'users';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#user-active-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#user-active-modal').modal('hide');
            }
        });
    });

    $('.disable-user').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/disable/" + user_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'users';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#user-disable-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#user-disable-modal').modal('hide');
            }
        });
    });

    $('.delete-user').click(function() {
        $.ajax({
            type: 'GET',
            url: "../users/delete/" + user_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'users';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#user-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#user-delete-modal').modal('hide');
            }
        });
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
    var place = address = '';

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
    });

    // Modal
    $('.appreviews-table .btn-danger').click(function() {
        place = $(this).parent().parent().data('place');
        address = $(this).parent().parent().data('address');
        $('#appreviews-delete-modal').modal('show');
    });
    // Button
    $('.appreviews-table .btn-primary').click(function() {
        place = $(this).parent().parent().data('place');
        address = $(this).parent().parent().data('address');
        window.location = $('#base_url').val() + "appreview/" + place + "/" + address;
    });
    $('.delete-appreviews').click(function() {
        $.ajax({
            type: 'GET',
            url: "../appreviews/delete/" + place + "/" + address,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'appreviews';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#appreviews-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#appreviews-delete-modal').modal('hide');
            }
        });
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
    });

    // Modal
    $('.nondogfriendlys-table .btn-danger').click(function() {
        place = $(this).parent().parent().data('place');
        address = $(this).parent().parent().data('address');
        $('#nondogfriendlys-delete-modal').modal('show');
    });
    // Button
    $('.nondogfriendlys-table .btn-primary').click(function() {
        place = $(this).parent().parent().data('place');
        address = $(this).parent().parent().data('address');
        window.location = $('#base_url').val() + "nondogfriendly/" + place + "/" + address;
    });
    $('.delete-nondogfriendlys').click(function() {
        $.ajax({
            type: 'GET',
            url: "../nondogfriendlys/delete/" + place + "/" + address,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'nondogfriendlys';
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#nondogfriendlys-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#nondogfriendlys-delete-modal').modal('hide');
            }
        });
    });
    
    /*
     * App Review Page
     */
    var report_id = 0;

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
    });

    // Modal
    $('.appreview-table .btn-danger').click(function() {
        report_id = $(this).parent().parent().data('id');
        address = $(this).parent().parent().data('address');
        $('#appreview-delete-modal').modal('show');
    });
    // Button
    $('.delete-appreview').click(function() {
        $.ajax({
            type: 'GET',
            url: "../../appreview/delete/" + report_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'appreview/' + $('#report_place').val() + "/" + $('#report_address').val();
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#appreview-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#appreview-delete-modal').modal('hide');
            }
        });
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
    });

    // Modal
    $('.nondogfriendly-table .btn-danger').click(function() {
        report_id = $(this).parent().parent().data('id');
        address = $(this).parent().parent().data('address');
        $('#nondogfriendly-delete-modal').modal('show');
    });
    // Button
    $('.delete-nondogfriendly').click(function() {
        $.ajax({
            type: 'GET',
            url: "../../nondogfriendly/delete/" + report_id,
            success: function(resposne) {
                var data = JSON.parse(resposne);
                console.log(data);
                if ( data.status == 'success' ) {
                    window.location = $('#base_url').val() + 'nondogfriendly/' + $('#report_place').val() + "/" + $('#report_address').val();
                } else {
                    setTimeout(function() {
                        $.bootstrapGrowl(data.message, {
                            type: 'danger',
                            allow_dismiss: true
                        });
                    }, 1000);
                }
                $('#nondogfriendly-delete-modal').modal('hide');
            },
            error: function(data) {
                setTimeout(function() {
                    $.bootstrapGrowl('Server Error', {
                        type: 'danger',
                        allow_dismiss: true
                    });
                }, 1000);
                $('#nondogfriendly-delete-modal').modal('hide');
            }
        });
    });
})