<?php
/*
Plugin Name: 1337 FB & Twitter Feeds
Plugin URI: https://codelyfe.github.io/
Description: This plugin displays Facebook and Twitter feeds and allows users to set the URL and page ID.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

// I Provide Free Tech Support: https://support-desk.bss.design/index.html

[social_media_feeds]

*/

// Display Facebook feed
function facebook_feed() {
  $page_id = get_option( 'page_id' );
  $fb_access_token = get_option( 'fb_access_token' );

  $url = 'https://graph.facebook.com/' . $page_id . '/posts?access_token=' . $fb_access_token;

  $response = wp_remote_get( $url );

  if ( !is_wp_error( $response ) ) {
    $content = json_decode( wp_remote_retrieve_body( $response ) );

    if ( isset( $content->data ) ) {
      echo '<ul>';

      foreach ( $content->data as $post ) {
        echo '<li><a href="' . $post->link . '">' . $post->message . '</a></li>';
      }

      echo '</ul>';
    }
  }
}

// Display Twitter feed
function twitter_feed() {
  $twitter_username = get_option( 'twitter_username' );

  $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $twitter_username . '&count=5';

  $response = wp_remote_get( $url, array(
    'headers' => array(
      'Authorization' => 'Bearer ' . get_option( 'twitter_bearer_token' ),
    ),
  ) );

  if ( !is_wp_error( $response ) ) {
    $content = json_decode( wp_remote_retrieve_body( $response ) );

    if ( isset( $content ) ) {
      echo '<ul>';

      foreach ( $content as $tweet ) {
        echo '<li><a href="https://twitter.com/' . $twitter_username . '/status/' . $tweet->id_str . '">' . $tweet->text . '</a></li>';
      }

      echo '</ul>';
    }
  }
}

// Create settings menu
function social_media_feeds_menu() {
  add_options_page(
    'Social Media Feeds Settings',
    'Social Media Feeds',
    'manage_options',
    'social-media-feeds-settings',
    'social_media_feeds_settings_page'
  );

  add_action( 'admin_init', 'register_social_media_feeds_settings' );
}

function register_social_media_feeds_settings() {
  register_setting( 'social-media-feeds-settings-group', 'page_id' );
  register_setting( 'social-media-feeds-settings-group', 'fb_access_token' );
  register_setting( 'social-media-feeds-settings-group', 'twitter_username' );
  register_setting( 'social-media-feeds-settings-group', 'twitter_bearer_token' );
}

function social_media_feeds_settings_page() {
?>
  <div class="wrap">
    <h1>Social Media Feeds Settings</h1>

    <form method="post" action="options.php">
      <?php settings_fields( 'social-media-feeds-settings-group' ); ?>
      <?php do_settings_sections( 'social-media-feeds-settings-group' ); ?>

      <table class="form-table">
        <tr valign="top">
          <th scope="row">Facebook Page ID:</th>
          <td>
            <input type="text" name="page_id" value="<?php echo esc_attr( get_option('page_id') ); ?>" />
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Facebook Access Token:</th>
          <td>
            <input type="text" name="fb_access_token" value="<?php echo esc_attr( get_option('fb_access_token') ); ?>" />
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Twitter Username:</th>
          <td>
            <input type="text" name="twitter_username" value="<?php echo esc_attr( get_option('twitter_username') ); ?>" />
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Twitter Bearer Token:</th>
          <td>
            <input type="text" name="twitter_bearer_token" value="<?php echo esc_attr( get_option('twitter_bearer_token') ); ?>" />
          </td>
        </tr>
      </table>

      <?php submit_button(); ?>
    </form>
  </div>
<?php
}

add_action( 'admin_menu', 'social_media_feeds_menu' );

// Create shortcode
function social_media_feeds_shortcode() {
  ob_start();
?>
  <div>
    <?php facebook_feed(); ?>
    <?php twitter_feed(); ?>
  </div>
<?php
  return ob_get_clean();
}

add_shortcode( 'social_media_feeds', 'social_media_feeds_shortcode' );
