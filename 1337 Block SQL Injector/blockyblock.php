<?php
/*
Plugin Name: 1337 URL SQL Injection Blocker
Plugin URI: https://codelyfe.github.io/
Description: A simple plugin to block SQL injections via URLs.
Version: 1337.0
Author: ランダル・バーガー・ジュニア
Author URI: https://codelyfe.github.io/
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function block_url_sql_injection() {
    // Get the requested URL
    $request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
    // Check if the request contains a SQL injection pattern
    if (preg_match('/(union|select|insert|update|delete|from|where|drop table)/i', $request_uri)) {
        wp_die('Sorry, you are not allowed to access this page.');
    }
}

add_action('wp', 'block_url_sql_injection');