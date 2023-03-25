<?php
/*
Plugin Name: 1337 Background Image Plugin
Description: Allows users to change their background image with a URL
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Add a new settings page to the WordPress admin menu
function background_image_add_menu_item() {
    add_menu_page(
        'Background Image Settings',
        'Background Image',
        'manage_options',
        'background-image-settings',
        'background_image_settings_page'
    );
}
add_action( 'admin_menu', 'background_image_add_menu_item' );

// Create the settings page
function background_image_settings_page() {

    // Check that the user is authorized to access the settings page
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Save the background image URL when the form is submitted
    if ( isset( $_POST['background_image_url'] ) ) {
        update_user_meta( get_current_user_id(), 'background_image_url', $_POST['background_image_url'] );
        echo '<div class="notice notice-success><p>Background image updated successfully.</p></div>';
    }

    // Display the settings page
    $background_image_url = get_user_meta( get_current_user_id(), 'background_image_url', true );
    ?>
    <div class="wrap">
        <h1>Background Image Settings</h1>
        <form method="post">
            <label for="background_image_url">Background Image URL:</label>
            <input type="text" name="background_image_url" id="background_image_url" value="<?php echo $background_image_url; ?>" /><br />
            <input type="submit" value="Save" class="button button-primary" />
        </form>
    </div>
    <?php
}

// Apply the background image to the page
function background_image_enqueue_scripts() {

    // Get the background image URL for the current user
    $background_image_url = get_user_meta( get_current_user_id(), 'background_image_url', true );

    // Only apply the background image if the user has set one
    if ( ! empty( $background_image_url ) ) {
        // Load the CSS file that applies the background image
        wp_enqueue_style( 'background-image-style', plugins_url( '/style.css', __FILE__ ) );

        // Add the inline style tag to the page to apply the background image
        echo '<style>body { background-image: url(\'' . esc_url( $background_image_url ) . '\') !important; }</style>';
    }
}
add_action( 'wp_enqueue_scripts', 'background_image_enqueue_scripts' );
add_action( 'wp_head', 'background_image_enqueue_scripts' );
