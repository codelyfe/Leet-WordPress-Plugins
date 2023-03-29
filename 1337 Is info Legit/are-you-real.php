<?php 
/* 
Plugin Name: 1337 Login Checkbox Validation 
Plugin URI: https://codelyfe.github.io 
Description: Adds a checkbox to the login form to validate the user's information 
Version: 1337.0 
Author: Randal Burger Jr 
Author URI: https://codelyfe.github.io 
*/ 

// Hook into the login form to add the custom checkbox 
add_action( 'login_form', 'add_custom_checkbox' ); 

function add_custom_checkbox() { 
?> 
    <p> 
        <input type="checkbox" name="custom_checkbox" id="custom_checkbox"> 
        <label for="custom_checkbox">I confirm that my information is correct</label> 
    </p> 
<?php 
} 

// Hook into the authentication process to check the custom checkbox 
add_filter( 'authenticate', 'check_custom_checkbox', 10, 3 ); 

function check_custom_checkbox( $user, $username, $password ) { 
    $custom_checkbox = isset( $_POST['custom_checkbox'] ) ? $_POST['custom_checkbox'] : ''; 

    if ( empty( $custom_checkbox ) ) { 
        // If custom checkbox is unchecked, throw an error 
        $error = new WP_Error(); 
        $error->add( 'unchecked_custom_checkbox', 'Please confirm that your information is correct.' ); 
        return $error; 
    } 

    // Otherwise, continue with the authentication process 
    return $user; 
} 
