<?php
/*
 Plugin Name: 1337 Lazy Image Loader
 Plugin URI: https://codelyfe.github.io
 Description: Enables lazy loading of images on your WordPress site.
 Version: 1337.0
 Author: Randal Burger Jr
 Author URI: https://codelyfe.github.io
 License: GPL2 or later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_option('lazy_load_images_enabled', true);
add_option('lazy_load_images_expired_url', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

function lazy_load_images_enqueue_script() {
    if (get_option('lazy_load_images_enabled', true)) {
        wp_enqueue_script('lazy-load-images', plugin_dir_url(__FILE__) . 'lazy-load-images.js', array(), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'lazy_load_images_enqueue_script');

function lazy_load_images_register_menu_page() {
    add_options_page('Lazy Load Images', 'Lazy Load Images', 'manage_options', 'lazy-load-images', 'lazy_load_images_render_settings_page');
}
add_action('admin_menu', 'lazy_load_images_register_menu_page');

function lazy_load_images_render_settings_page() {
    if (isset($_POST['lazy_load_images_enabled'])) {
        update_option('lazy_load_images_enabled', true);
    } else {
        update_option('lazy_load_images_enabled', false);
    }
    ?>
    <div class="wrap">
        <h1>Lazy Load Images Settings</h1>
        <form method="post">
            <label for="lazy_load_images_enabled">
                <input type="checkbox" id="lazy_load_images_enabled" name="lazy_load_images_enabled" value="1" <?= get_option('lazy_load_images_enabled', true) ? 'checked' : '' ?>>
                Enable Lazy Loading
            </label><br><br>
            <p><em>Note: This plugin uses the Intersection Observer API, which is not supported by certain older browsers.</em></p>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
