<?php
    /*
     Plugin Name: 1337 My Brute Forcer
     Plugin URI: https://codelyfe.github.io
     Description: My WordPress Plugin
     Version: 1337.0
     Author: Randal Burger Jr
     Author URI: https://codelyfe.github.io
    */

function my_login_failed( $username ) {
  // do something
}

add_action( 'wp_login_failed', 'my_login_failed' );

function my_authenticate( $user ) {
    if ( $user == NULL ) {
        // Something went wrong while authenticate
    }
    else {
        // Trivial brute login detection
        $last_failed_login = get_transient($user->user_login . '_failed_login');
        if ($last_failed_login !== false && absint($last_failed_login['attempts']) >= 6) {
            wp_die( 'Too many failed attempts. Try again in 30 minutes.' );
        }
    }
    return $user;
}

add_filter( 'authenticate', 'my_authenticate', 30, 3 );