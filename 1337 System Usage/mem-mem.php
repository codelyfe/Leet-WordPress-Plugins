<?php
/*
Plugin Name: 1337 System Resources
Plugin URI: https://codelyfe.github.io
Description: Displays memory and disk usage.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io
*/

function my_plugin_display_menu_page() {
    ?>
    <div class="wrap">
        <h1>System Resource Monitor</h1>
        <p><input type="button" class="button" id="refresh-system-resources" value="Refresh"></p>
        <div id="system-resources-content"></div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            function refreshSystemResources() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: { action: 'my_plugin_get_system_resources' },
                    success: function(response) {
                        $('#system-resources-content').html(response);
                    }
                });
            }
            $('#refresh-system-resources').click(function() {
                refreshSystemResources();
            });
            setInterval(refreshSystemResources, 5000); // Refresh system resources every 5 seconds
            refreshSystemResources(); // Initial refresh
        });
    </script>
    <?php
}

function my_plugin_get_system_resources() {
    $memory_usage = memory_get_usage();
    $memory_usage_mb = round($memory_usage / 1024 / 1024, 2); // Convert to MB with two decimal places
    $disk_total_space = disk_total_space('/');
    $disk_free_space = disk_free_space('/');
    $disk_total_space_gb = round($disk_total_space / 1024 / 1024 / 1024, 2); // Convert to GB with two decimal places
    $disk_free_space_gb = round($disk_free_space / 1024 / 1024 / 1024, 2); // Convert to GB with two decimal places
    $system_resources_message = sprintf(__('Memory usage: %s MB<br>Disk usage: %s GB (out of %s GB)<br>Free space: %s GB'), $memory_usage_mb, $disk_total_space_gb - $disk_free_space_gb, $disk_total_space_gb, $disk_free_space_gb);
    echo '<p>' . $system_resources_message . '</p>';
    wp_die();
}
add_action('wp_ajax_my_plugin_get_system_resources', 'my_plugin_get_system_resources');

function myi_plugin_register_settings() {
    add_settings_section('my_plugin_system_resources', __('1337 System Resources', 'my_plugin'), 'my_plugin_system_resources_callback', 'general');
    add_settings_field('my_plugin_memory_usage', __('Memory Usage', 'my_plugin'), 'my_plugin_memory_usage_callback', 'general', 'my_plugin_system_resources');
    add_settings_field('my_plugin_disk_usage', __('Disk Usage', 'my_plugin'), 'my_plugin_disk_usage_callback', 'general', 'my_plugin_system_resources');
    register_setting('general', 'my_plugin_system_resources');
}
add_action('admin_init', 'myi_plugin_register_settings');

function my_plugin_system_resources_callback() {
    echo '<p>' . __('Displays memory and disk usage.', 'my_plugin') . '</p>';
}

function my_plugin_memory_usage_callback() {
    $memory_usage = memory_get_usage();
    $memory_usage_mb = round($memory_usage / 1024 / 1024, 2); // Convert to MB with two decimal places
    echo $memory_usage_mb . ' MB';
}

function my_plugin_disk_usage_callback() {
    $disk_total_space = disk_total_space('/');
    $disk_free_space = disk_free_space('/');
    $disk_total_space_gb = round($disk_total_space / 1024 / 1024 / 1024, 2); // Convert to GB with two decimal places
    $disk_free_space_gb = round($disk_free_space / 1024 / 1024 / 1024, 2); // Convert to GB with two decimal places
    $disk_usage_gb = $disk_total_space_gb - $disk_free_space_gb;
    echo $disk_usage_gb . ' GB (out of ' . $disk_total_space_gb . ' GB), ' . $disk_free_space_gb . ' GB free';
}

function my_plugin_add_menu_page() {
    add_options_page(__('System Resources', 'my_plugin'), __('System Resources', 'my_plugin'), 'manage_options', 'my-plugin-system-resources', 'my_plugin_display_menu_page');
}
add_action('admin_menu', 'my_plugin_add_menu_page');