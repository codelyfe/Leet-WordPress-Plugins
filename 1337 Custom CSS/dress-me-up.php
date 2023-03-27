<?php
/*
Plugin Name: 1337 Custom CSS
Description: Allows users to add custom CSS to the main theme.
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
// I Provide Free Tech Support: https://support-desk.bss.design/index.html

// Add the admin menu
add_action('admin_menu', 'your_custom_css_add_admin_menu');

function your_custom_css_add_admin_menu() {
    // Add the option under Appearance menu
    add_submenu_page( 
        'themes.php', 
        '1337 Custom CSS', 
        '1337 Custom CSS', 
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
    echo '<h1 style="text-align:center;">1337 Custom CSS Options</h1>';
    echo '<form method="post">';
    echo '<label for="css-editor">Add your custom CSS below:</label><br>';

    // Replace textarea with CodeMirror textarea
    echo '<textarea id="css-editor" name="your_custom_css" style="height:500px;">' . $your_custom_css . '</textarea><br>';

    echo '<input type="submit" name="submit" value="Save Custom CSS" class="button-primary">';
    echo '</form>';
    echo '</div>';

    // Add script tag to initialize CodeMirror on the textarea
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/css/css.js"></script>';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.css">';
    echo '<script>';
    echo 'var editor = CodeMirror.fromTextArea(document.getElementById("css-editor"), {';
    echo '    lineNumbers: true,';
    echo '    mode: "css",';
    echo '    theme: "default",';
    echo '});';
    echo '</script>';
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