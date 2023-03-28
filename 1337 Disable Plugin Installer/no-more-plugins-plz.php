<?php
/*
Plugin Name: 1337 Disable Plugin Install
Plugin URI: https://codelyfe.github.io
Description: This plugin disables the plugin installation page.
Version: 1337.0
Author: Randal Burger
Author URI: https://codelyfe.github.io
License: GPL2
*/

function disable_plugin_install_redirect() {
    global $pagenow;

    if ( $pagenow == 'plugin-install.php' ) {
        wp_redirect( admin_url() );
        exit;
    }
}
add_action( 'admin_init', 'disable_plugin_install_redirect' );

?>