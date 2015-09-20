<?php
/*
Plugin Name: WP Strava Connect
Plugin URI: http://wellrootedmedia.com/plugins/wp-strava-plugin
Description: This plugin is to read data for users and display it on their website.
Author: Well Rooted Media
Version: 0.0.1
Author URI: http://wellrootedmedia.com
*/

/*
 * Included files
 */
include(plugin_dir_path(__FILE__) . 'ApiSetup.php');
include(plugin_dir_path(__FILE__) . 'StravaApi.php');
include(plugin_dir_path(__FILE__) . 'wscShortCodes.php');
include(plugin_dir_path(__FILE__) . 'wscWidget.php');
add_action('wp_enqueue_scripts', 'wscEnqueueStyleScripts');
function wscEnqueueStyleScripts()
{
    /*
     * styles
     */
//    wp_enqueue_style('bootstrap-min-style', get_template_directory_uri() . '/dist/css/bootstrap.min.css');

    /*
     * scripts
     */
    wp_enqueue_script( 'jquery-connect', plugins_url( 'js/jquery.connect.js' , __FILE__ ), array('jquery'), '0.0.1', true);
//    wp_enqueue_script('jquery-connect', plugin_dir_path(__FILE__) . 'js/jquery.connect.js', array('jquery'), '0.0.1', true);
}

/*
 * Register activation hooks
 */
register_activation_hook(__FILE__, 'wsc_install');
register_activation_hook(__FILE__, 'wsc_uninstall');

add_action('init', 'myStartSession', 1);
function myStartSession()
{
    if (!session_id()) {
        session_start();
    }
}

function wsc_install()
{
    global $wp_version;
    if (version_compare($wp_version, "4.1", "<")) {
        deactivate_plugins(basename(__FILE__));
        wp_die('This plugin requires WordPress version 4.1 or higher');
    }
}

function wsc_uninstall()
{
    // do something
}



//add_action('admin_menu', 'register_strava_connect_menu_page');
//function register_strava_connect_menu_page()
//{
//    add_menu_page(
//        'Connect with Strava',
//        'Strava Connect',
//        'manage_options',
//        __FILE__,
//        'wscSettingsPage',
//        plugins_url('myplugin/images/icon.png'),
//        3
//    );
//}