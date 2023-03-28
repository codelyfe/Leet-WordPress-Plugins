jQuery(document).ready(function($) {
    $.post(uploads_monitor_params.ajax_url, {
        action: 'uploads_monitor_get_files_count',
        nonce: uploads_monitor_params.nonce
    }, function(response) {
        $('#uploads-monitor-count').text(response.count);
    });
});