<?php
/*
Plugin Name: 1337 Cron Job Cleanup
Plugin URI: https://codelyfe.github.io
Description: Cleans up unused cron jobs
Version: 1337.0
Author: Randal Burger Jr
Author URI: https://codelyfe.github.io
License: GPL2
*/

function cj_cleanup() {
    // Get the scheduled cron jobs
    $cron = get_option('cron');

    // Loop through each cron job
    foreach ($cron as $time => $hooks) {
        foreach ($hooks as $hook => $args) {
            // Check if the cron job has already passed
            if ($time < current_time('timestamp')) {
                // Delete the cron job if it is no longer being used
                wp_unschedule_event($time, $hook, $args);
            }
        }
    }
}

// Schedule the cleanup function to run every day at midnight
add_action('wp', function() {
    if (!wp_next_scheduled('cj_cleanup')) {
        wp_schedule_event(time(), 'daily', 'cj_cleanup');
    }
});

// Add the action hook for the cleanup function
add_action('cj_cleanup', 'cj_cleanup');