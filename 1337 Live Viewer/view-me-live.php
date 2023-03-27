<?php
/*
Plugin Name: 1337 YT Live Streamer
Plugin URI: https://codelyfe.github.io
Description: Displays Youtube live stream on any page or post!
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io
License: GPL2
*/
// I Provide Free Tech Support: https://support-desk.bss.design/index.html

// Register the live stream settings page
function add_live_stream_settings_page() {
  add_submenu_page(
    'options-general.php',
    'Live Stream Settings',
    'Live Stream',
    'manage_options',
    'live_stream_settings',
    'live_stream_settings_page_callback'
  );
}
add_action('admin_menu', 'add_live_stream_settings_page');

// Add the live stream ID field to the settings page
function live_stream_settings_page_callback() {
  ?>
  <div class="wrap">
    <h1>Youtube Live Stream Settings</h1>
    <p>
    You can embed the live YouTube stream on any post or page using the shortcode [livestream].
    </p>
    <form method="post" action="options.php">
      <?php settings_fields('live_stream_settings_group'); ?>
      <?php do_settings_sections('live_stream_settings_group'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Live Stream ID:</th>
          <td><input type="text" name="live_stream_id" value="<?php echo esc_attr(get_option('live_stream_id')); ?>" /></td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

// Register the live stream ID option
function register_live_stream_settings() {
  register_setting(
    'live_stream_settings_group',
    'live_stream_id'
  );
}
add_action('admin_init', 'register_live_stream_settings');

// Display the live stream using the shortcode
function live_stream($atts) {
  // Get the live stream ID from the options
  $live_stream_id = get_option('live_stream_id');

  // Generate the HTML for the live stream
  $html = '<iframe id="live-stream" width="560" height="315" src="https://www.youtube.com/embed/' . $live_stream_id . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
  
  // Output the HTML
  echo $html;
}
add_shortcode('livestream', 'live_stream');