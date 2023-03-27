<?php
/*
Plugin Name: 1337 Custom Header Code Plugin
Description: Allows users to add custom code to the head section of every page
Version: 1337.0
Author: ランダル・バーガー・ジュニア
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
// I Provide Free Tech Support: https://support-desk.bss.design/index.html

// Add a new settings page to the WordPress admin menu
function custom_add_menu_item() {
    add_menu_page(
        'Custom Header Code Settings',
        'Custom Header Code',
        'manage_options',
        'custom-settings',
        'custom_settings_page'
    );
}
add_action( 'admin_menu', 'custom_add_menu_item' );

// Create the settings page
function custom_settings_page() {

    // Check that the user is authorized to access the settings page
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Save the custom code when the form is submitted
    if ( isset( $_POST['custom_code'] ) ) {
        update_option( 'custom_code', $_POST['custom_code'] );
        echo '<div class="notice notice-success"><p>Custom code updated successfully.</p></div>';
    }

    // Display the settings page
    $custom_code = get_option( 'custom_code', '' );
    ?>
    <div class="wrap">
        <h1>Custom Code Settings</h1>
        <form method="post">
            <label for="header-code-editor">Custom Header Code:</label>
            <!-- Replace textarea with CodeMirror textarea -->
            <textarea name="custom_code" id="header-code-editor"><?php echo esc_textarea( $custom_code ); ?></textarea><br />
            <input type="submit" value="Save" class="button button-primary" />
        </form>
    </div>
    
    <!-- Add script tag to initialize CodeMirror on the textarea -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/javascript/javascript.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.css">
    <script>
      var editor = CodeMirror.fromTextArea(document.getElementById("header-code-editor"), {
        lineNumbers: true,
        //mode: "javascript",
        theme: "default",
      });
    </script>
    <?php
}

// Add the custom code to the head section of every page
function custom_add_to_head() {
    $custom_code = get_option( 'custom_code', '' );
    if ( ! empty( $custom_code ) ) {
        echo $custom_code;
    }
}
add_action( 'wp_head', 'custom_add_to_head' );