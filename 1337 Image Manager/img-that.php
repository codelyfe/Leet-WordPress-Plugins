<?php
/*
Plugin Name: 1337 Image Manager
Description: Allows admins to manage images in the WordPress upload directory and view which images are in use on the website
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * Enqueue the necessary stylesheets
 */
function image_manager_enqueue_styles() {
    wp_enqueue_style( 'image-manager-styles', plugins_url( '/image-manager.css', __FILE__ ) );
  }
  add_action( 'admin_enqueue_scripts', 'image_manager_enqueue_styles' );
  
  /**
   * Get an array of images that are in use on the website
   */
  function image_manager_get_used_images() {
    global $wpdb;
  
    $post_types = array( 'post', 'page' );
    $meta_key = '_thumbnail_id';
  
    $result = $wpdb->get_results( "
      SELECT DISTINCT meta_value
      FROM {$wpdb->postmeta}
      WHERE meta_key = '{$meta_key}'
    " );
  
    $used_images = array();
  
    foreach ( $result as $image ) {
      $image_id = $image->meta_value;
      $image_url = wp_get_attachment_image_src( $image_id );
      $image_file = str_replace( get_site_url(), '', $image_url[0] );
      $used_images[] = basename( $image_file );
    }
  
    return $used_images;
  }
  
  /**
   * Scan the content of the website and return a list of images that are not used
   */
  function image_manager_get_unused_images() {
    // Retrieve the list of images in the WordPress uploads directory
    $uploads_dir = wp_upload_dir();
    $image_files = scandir( $uploads_dir['path'] );
  
    $used_images = image_manager_get_used_images();
    $unused_images = array_diff($image_files, $used_images);
  
    return $unused_images;
  }
  
  /**
   * Render the options screen
   */
  function image_manager_options_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
      wp_die( __( 'You do not have sufficient permissions to access this page.', 'image-manager' ) );
    }
  
    // Retrieve the lists of images in the WordPress uploads directory and in use on the website
    $uploads_dir = wp_upload_dir();
    $image_files = scandir( $uploads_dir['path'] );
    $used_images = image_manager_get_used_images();
    $unused_images = image_manager_get_unused_images();
  
    // Save image deletions if form is submitted
    if ( isset( $_POST['image_manager_images'] ) ) {
      foreach ( $_POST['image_manager_images'] as $image_file ) {
        unlink( $uploads_dir['path'] . '/' . $image_file );
      }
      echo '<div class="notice notice-success is-dismissible">';
      echo '<p>' . __( 'Images deleted successfully.', 'image-manager' ) . '</p>';
      echo '</div>';
  
      // Refresh the list of used and unused images after deleting images
      $used_images = image_manager_get_used_images();
      $unused_images = image_manager_get_unused_images();
    }
    ?>
    <div class="wrap">
      <h1><u><?php echo esc_html( get_admin_page_title() ); ?></u></h1><br/>
      <h2>Used and Unused Images</h2>
      <form method="post" action="">
        <?php wp_nonce_field( 'image_manager_options_verify' ); ?>
        <table class="wp-list-table widefat fixed striped">
          <thead>
            <tr>
              <th class="manage-column check-column"><input type="checkbox" /></th>
              <th class="manage-column"><?php esc_html_e( 'Image', 'image-manager' ); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $image_files as $image_file ) :
            if ( $image_file !== '.' && $image_file !== '..' ) : ?>
              <tr>
                <td class="check-column"><input type="checkbox" name="image_manager_images[]" value="<?php echo esc_attr( $image_file ); ?>" <?php checked( in_array( $image_file, $used_images ) ); ?> <?php echo ( in_array( $image_file, $used_images ) ) ? 'disabled' : ''; ?> /></td>
                <td><img style="height:200px;" src="<?php echo esc_url( $uploads_dir['url'] . '/' . $image_file ); ?>" alt="<?php echo esc_attr( $image_file ); ?>" /></td>
              </tr>
            <?php
            endif;
            endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th class="manage-column check-column"><input type="checkbox" /></th>
              <th class="manage-column"><?php esc_html_e( 'Image', 'image-manager' ); ?></th>
            </tr>
          </tfoot>
        </table>
        <p class="description"><?php _e( 'Note: Disabled checkboxes indicate images that are currently in use and cannot be deleted.', 'image-manager' ) ?></p>
        <?php submit_button( __( 'Delete Selected Images', 'image-manager' ), 'delete-selected', 'submit' ); ?>
      </form>
      <br/><br/>
      <h2><?php esc_html_e( 'Unused Images', 'image-manager' ); ?></h2>
      <form method="post" action="">
        <?php wp_nonce_field( 'image_manager_options_verify' ); ?>
        <table class="wp-list-table widefat fixed striped">
          <thead>
            <tr>
              <th class="manage-column check-column"><input type="checkbox" /></th>
              <th class="manage-column"><?php esc_html_e( 'Image', 'image-manager' ); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $unused_images as $image_file ) :
            if ( $image_file !== '.' && $image_file !== '..' ) : ?>
              <tr>
                <td class="check-column"><input type="checkbox" name="image_manager_images[]" value="<?php echo esc_attr( $image_file ); ?>" /></td>
                <td><img style="height:200px;" src="<?php echo esc_url( $uploads_dir['url'] . '/' . $image_file ); ?>" alt="<?php echo esc_attr( $image_file ); ?>" /></td>
              </tr>
            <?php
            endif;
            endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th class="manage-column check-column"><input type="checkbox" /></th>
              <th class="manage-column"><?php esc_html_e( 'Image', 'image-manager' ); ?></th>
            </tr>
          </tfoot>
        </table>
        <p class="description"><?php _e( 'Note: These images are not currently in use and can be safely deleted.', 'image-manager' ) ?></p>
        <?php submit_button( __( 'Delete Selected Images', 'image-manager' ), 'delete-selected', 'submit' ); ?>
      </form>
    </div>
  <?php
  }
  
  /**
   * Add a submenu under the media menu
   */
  function image_manager_add_submenu() {
    add_media_page(
      __( '1337 Image Manager', 'image-manager' ),
      __( '1337 Image Manager', 'image-manager' ),
      'manage_options',
      'image-manager',
      'image_manager_options_page'
    );
  }
  add_action( 'admin_menu', 'image_manager_add_submenu' );