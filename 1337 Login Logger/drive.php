<?php
/*
Plugin Name: 1337 Login Logger
Plugin URI: https://codelyfe.github.io/
Description: A plugin that logs user login times and displays them in the settings page.
Version: 1337.0
Author: ランダル・バーガー・ジュニア
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Log login times
function log_login_time($user_login, $user) {
  $log = $user_login . ' logged in at ' . current_time('mysql') . "\n";
  error_log($log, 3, WP_CONTENT_DIR . '/login-log.txt');
}
add_action('wp_login', 'log_login_time', 10, 2);

// Add settings page
function login_logger_menu() {
  add_options_page(
    'Login Logger Settings',
    'Login Logger',
    'manage_options',
    'login-logger',
    'login_logger_settings_page'
  );

  add_action( 'admin_init', 'register_login_logger_settings' );
}

function register_login_logger_settings() {
  // No registration needed
}

function login_logger_settings_page() {
?>
  <div class="wrap">
    <h1>Login Logger Settings</h1>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Login Times:</th>
        <td>
          <textarea rows="10" readonly><?php readfile(WP_CONTENT_DIR . '/login-log.txt'); ?></textarea>
        </td>
      </tr>
    </table>
  </div>
<?php
}

// Add plugin actions
add_action( 'admin_menu', 'login_logger_menu' );