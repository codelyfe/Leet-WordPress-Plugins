<?php
/*
Plugin Name: 1337 Your Custom CSS
Description: Allows users to add custom CSS to the main theme.
Version: 1337.0
Author: ランダル・バーガー・ジュニア
Author URI: https://codelyfe.github.io/
*/

// Add the admin menu
add_action('admin_menu', 'your_custom_css_add_admin_menu');

function your_custom_css_add_admin_menu() {
    // Add the option under Appearance menu
    add_submenu_page( 
        'themes.php', 
        '1337 Your Custom CSS', 
        '1337 Your Custom CSS', 
        'manage_options', 
        'your-custom-css', 
        'your_custom_css_options_page' 
    );
}

// Add the options page
function your_custom_css_options_page() {
    // Save the CSS if posted
    if (isset($_POST['submit'])) {
        update_option('your_custom_css', $_POST['your_custom_css']);
    }
    
    // Get the current CSS from options
    $your_custom_css = get_option('your_custom_css');
    
    // Output the HTML for the options page
    echo '<div class="wrap">';
    echo '<h1>Your Custom CSS Options</h1>';
    echo '<form method="post">';
    echo '<label for="your_custom_css">Add your custom CSS below:</label><br>';
    echo '<textarea id="your_custom_css" name="your_custom_css" rows="10">' . $your_custom_css . '</textarea><br>';
    echo '<input type="submit" name="submit" value="Save Custom CSS" class="button-primary">';
    echo '</form>';
    echo '</div>';
}

// Add the custom CSS to the main theme
add_action('wp_head', 'your_custom_css_add_custom_css');

function your_custom_css_add_custom_css() {
    $your_custom_css = get_option('your_custom_css');
    
    if (!empty($your_custom_css)) {
        echo '<style type="text/css">' . $your_custom_css . '</style>';
    }
}
?>