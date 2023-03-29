<?php
/*
Plugin Name: 1337 WP-Login CSS
Description: Allows you to customize wp-login screen.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GPL2
*/

// Add a new Login CSS option to the Settings menu
function custom_login_css_add_options_page() {
  add_options_page(
    '1337 Login CSS',
    '1337 Login CSS',
    'manage_options',
    'custom-login-css',
    'custom_login_css_display_options_page'
  );
}
add_action('admin_menu', 'custom_login_css_add_options_page');

// Display the Login CSS options page HTML
function custom_login_css_display_options_page() {
  $css = get_option('custom_login_css');
  ?>
  <div class="wrap">
    <h1>Custom Login CSS</h1>
    <form method="post" action="options.php">
      <?php settings_fields('custom_login_css_options'); ?>
      <label for="login_css">Custom CSS:</label>
      <textarea id="login_css" name="custom_login_css"><?php echo esc_textarea($css); ?></textarea>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

// Enqueue the custom CSS on the login page
function custom_login_css_enqueue_styles() {
  $css = get_option('custom_login_css');
  if (!empty($css)) {
    wp_enqueue_style('custom-login-css', plugins_url('custom-login.css', __FILE__));
    wp_add_inline_style('custom-login-css', $css);
  }
}
add_action('login_enqueue_scripts', 'custom_login_css_enqueue_styles');

// Register the Login CSS options with WordPress
function custom_login_css_register_options() {
  register_setting('custom_login_css_options', 'custom_login_css');
}
add_action('admin_init', 'custom_login_css_register_options');