<?php
/*
Plugin Name: 1337 Custom Background Color
Description: Allows users to pick the background color for their site
Version: 1337.0
Author: ランダル・バーガー・ジュニア
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Define a new WordPress Admin Menu endpoint
function custom_background_color_menu() {
    add_menu_page(
        "Background Color",
        "Background Color",
        "manage_options",
        "custom-background-color-settings",
        "custom_background_color_settings"
    );
}
add_action("admin_menu", "custom_background_color_menu");

// Create an Admin Option Page for your plugin
function custom_background_color_settings() {
    ?>
    <div class="wrap">
        <h1>Select Your Background Color</h1>
        <form method="post" action="options.php">
            <?php settings_fields("bg-color-group"); ?>
            <?php do_settings_sections("bg-color-group"); ?>
            <label for="bg-color">Background Color:</label>
            <input type="text" name="bg-color" value="<?php echo get_option("bg-color"); ?>"/>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Save the user's input using the WordPress Options API
function color_setting_init() {
    register_setting(
        "bg-color-group",
        "bg-color"
    );
}
add_action("admin_init", "color_setting_init");

// Apply the user's color selection as the background color on the site
function add_custom_style() {
    $bgColor = get_option("bg-color");
    if ($bgColor) {
        echo '<style>body { background-color: '.$bgColor.'; }</style>';
    }
}
add_action( 'wp_head', 'add_custom_style' );
