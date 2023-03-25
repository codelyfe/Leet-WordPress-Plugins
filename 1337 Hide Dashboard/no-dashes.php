<?php
/*
Plugin Name: 1337 Hide Dashboard Widgets Wrap
Plugin URI: https://codelyfe.github.io/
Description: This plugin hides the dashboard-widgets-wrap element in the WordPress admin dashboard.
Version: 1337.0
Author: ランダル・バーガー・ジュニア
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action( 'admin_enqueue_scripts', 'hide_dashboard_widgets_wrap' );

function hide_dashboard_widgets_wrap() {
    wp_enqueue_style( 'hide-dashboard-widgets-wrap', plugin_dir_url( __FILE__ ) . 'hide-dashboard-widgets-wrap.css' );
}