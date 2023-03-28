<?php
/*
 Plugin Name: 1337 Uploads Monitor
 Plugin URI: https://codelyfe.github.io
 Description: Monitors the number of files in the uploads folder using Ajax.
 Version: 1337.0
 Author: Randal Burger
 Author URI: https://codelyfe.github.io
 License: GPL2 or later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function uploads_monitor_settings_page() {
    $uploads_dir = wp_upload_dir();
    $files_count = iterator_count(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads_dir['basedir'], FilesystemIterator::SKIP_DOTS)));
    ?>
    <div class="wrap">
        <h2>Uploads Folder Monitor</h2>
        <p><b>Current count of files in the uploads folder:</b> <span id="uploads-monitor-count"><?php echo $files_count; ?></span></p>
    </div>
    <?php
}

function uploads_monitor_add_menu_page() {
    add_management_page('Uploads Monitor', 'Uploads Monitor', 'manage_options', 'uploads_monitor', 'uploads_monitor_settings_page');
    wp_enqueue_script('uploads-monitor', plugin_dir_url(__FILE__) . 'uploads-monitor.js', array('jquery'), '1.0.0', true);
    wp_localize_script('uploads-monitor', 'uploads_monitor_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('uploads_monitor_nonce')
    ));
}
add_action('admin_menu', 'uploads_monitor_add_menu_page');

function uploads_monitor_get_files_count() {
    $uploads_dir = wp_upload_dir();
    $files_count = iterator_count(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads_dir['basedir'], FilesystemIterator::SKIP_DOTS)));
    echo json_encode(array('count' => $files_count));
    wp_die();
}
add_action('wp_ajax_uploads_monitor_get_files_count', 'uploads_monitor_get_files_count');
add_action('wp_ajax_nopriv_uploads_monitor_get_files_count', 'uploads_monitor_get_files_count');