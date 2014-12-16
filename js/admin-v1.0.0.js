jQuery(function ($) {

    $('#sneek-sidebar').on('submit', function (evt) {
        evt.preventDefault();

        var $sidebar = $('#sidebar-name'),
            data = {};

        data.action = 'sneek_add_sidebar';

        $sidebar.parent().removeClass('form-invalid');

        if ('' === $.trim($sidebar.val())) {
            $sidebar.focus().parent().addClass('form-invalid');
        } else {

            data.sidebarName = $.trim($sidebar.val());

            $.post(ajaxurl, data, function (response) {
                if ('success' == response.status) {
                    $sidebar.val('').focus();
                    $('#custom-sidebars tbody').append(response.content);
                }
            });

        }

    });


    $('#custom-sidebars').on('click', '.remove-sidebar', function (evt) {

        evt.preventDefault();

        var confirmation = confirm('Are you sure you want to remove ' + $(this).data('name') + ' widget area?');

        if ( ! confirmation)
            return;

        var sidebarToRemove = $(this),
            data = {};

        data.action = 'sneek_remove_sidebar';
        data.sidebarID = sidebarToRemove.data('sidebar-id');

        var rowToRemove = sidebarToRemove.parents('tr');
        rowToRemove.css('backgroundColor', '#FFEBE8');

        $.post(ajaxurl, data, function (response) {

            if ('success' == response.status) {
                rowToRemove.fadeOut('slow', function () {
                    $(this).remove();
                });
            } else {
                console.log('Unable to remove sidebar');
            }

        });

    });

});