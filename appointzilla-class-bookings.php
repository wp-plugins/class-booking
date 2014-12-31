<?php
/**
 * Plugin Name: Appointzilla Class Bookings (Lite)
 * Version: 0.3
 * Description: Appointzilla Class Bookings plugin is under development.
 * Author: Scientech It Solutions
 * Author URI: http://www.appointzilla.com
 * Plugin URI: http://www.appointzilla.com
 */

//ini_set('error_reporting', !E_NOTICE & !E_WARNING);

// Run 'Install' script on plugin activation
register_activation_hook( __FILE__, 'InstallScript' );
function InstallScript() {
    require_once('install-script.php');
}

// Translate all text & labels of plugin
add_action('plugins_loaded', 'LoadPluginLanguage');
 
function LoadPluginLanguage() {
 load_plugin_textdomain('appointzilla', FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}


// Admin dashboard Menu Pages For Booking Calendar Plugin ####
add_action('admin_menu','appointzilla_class_booking_menu');

function appointzilla_class_booking_menu() {
    //create new top-level menu 'appointment-calendar'
    //$menu = add_menu_page( 'Appointment Calendar', __('Appointment Calendar', 'appointzilla'), 'administrator', 'appointment-calendar', '', 'dashicons-calendar');


     //Classes Booking Menus
    $main_menu = add_menu_page( 'Class Booking', __('Class Booking', 'appointzilla'), 'administrator', 'admin-class-booking', '', 'dashicons-calendar');
    //admin class dashboard menu
    $sub_menu1 = add_submenu_page( 'admin-class-booking', __('Admin Booking', 'appointzilla'), __('Admin Booking', 'appointzilla'), 'administrator', 'admin-class-booking', 'display_admin_class_booking_page' );

    // Classes menu
    $sub_menu2 = add_submenu_page( 'admin-class-booking', __('Classes', 'appointzilla'), __('Classes', 'appointzilla'), 'administrator', 'classes', 'display_classes_page' );

    // Classes Clients
    $sub_menu3 = add_submenu_page( 'admin-class-booking', __('Clients', 'appointzilla'), __('Clients', 'appointzilla'), 'administrator', 'class-clients', 'display_class_clients_page' );

    // Manage class booking
    $sub_menu4 = add_submenu_page( 'admin-class-booking', __('Manage Bookings', 'appointzilla'), __('Manage Bookings', 'appointzilla'), 'administrator', 'manage-class-bookings', 'display_manage_class_bookings_page' );

    // Settings
    $sub_menu5 = add_submenu_page( 'admin-class-booking', __('Settings', 'appointzilla'), __('Settings', 'appointzilla'), 'administrator', 'class-booking-settings', 'display_class_booking_settings_page' );

    // Remove Plugin
    $sub_menu6 = add_submenu_page( 'admin-class-booking', 'Remove Plugin', __('Remove Plugin', 'appointzilla'), 'administrator', 'uninstall-plugin', 'display_uninstall_plugin_page' );

    // Support & Help
    $sub_menu7 = add_submenu_page( 'admin-class-booking', 'Help & Support', __('Help & Support', 'appointzilla'), 'administrator', 'help-n-support', 'display_help_n_support_page' );

    add_action( 'admin_print_styles-' . $main_menu, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu1, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu2, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu3, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu4, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu5, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu6, 'admin_pages_css_jss' );
    add_action( 'admin_print_styles-' . $sub_menu7, 'admin_pages_css_jss' );

}// end of menu function

function admin_pages_css_jss() {

    //jquery js
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');

    //clock-face time picker js css
    wp_register_script('clock-face-js', plugins_url('/menu-pages/clock-face/clock-face.js', __FILE__), '', '', true);
    wp_enqueue_script('clock-face-js');

    wp_register_style('clock-face-css', plugins_url('/menu-pages/clock-face/clock-face.css', __FILE__));
    wp_enqueue_style('clock-face-css');


    //full-calendar js n css
    wp_enqueue_script('full-calendar-moment-js',plugins_url('/menu-pages/full-calendar-assets/moment.min.js', __FILE__), array('jquery'));
    wp_enqueue_script('full-calendar',plugins_url('/menu-pages/full-calendar-assets/fullcalendar.js', __FILE__), array('jquery'));
    wp_enqueue_style('full-calendar-css',plugins_url('/menu-pages/full-calendar-assets/fullcalendar.css', __FILE__));


    //date picker js
    //wp_enqueue_script('jquery-ui-date-picker');

    //bootstrap js css
    wp_enqueue_script('tooltip',plugins_url('/menu-pages/bootstrap-assets/js/bootstrap-tooltip.js', __FILE__),array('jquery'));
    wp_enqueue_script('bootstrap-affix',plugins_url('/menu-pages/bootstrap-assets/js/bootstrap-affix.js', __FILE__));
    wp_enqueue_script('bootstrap-application',plugins_url('/menu-pages/bootstrap-assets/js/application.js', __FILE__));

    wp_register_style('bootstrap-css',plugins_url('/menu-pages/bootstrap-assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('bootstrap-css');

    //date picker css
    wp_enqueue_style('datepicker-css',plugins_url('/menu-pages/datepicker-assets/css/jquery-ui-1.8.23.custom.css', __FILE__));

    //font-awesome css
    wp_enqueue_style( 'font-awesome-css', plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__) );

}

//short-code detect
function shortcode_detect() {
    global $wp_query;
    $posts = $wp_query->posts;
    $pattern = get_shortcode_regex();

    foreach ($posts as $post) {
        if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'APCLASS', $matches[2] ) ) {

            //wp_register_script( $handle, $src, $deps, $ver, $in_footer );
            //wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

            //full-calendar js n css
            wp_enqueue_script('full-calendar-moment-js',plugins_url('/menu-pages/full-calendar-assets/moment.min.js', __FILE__), array('jquery'));
            wp_enqueue_script('full-calendar',plugins_url('/menu-pages/full-calendar-assets/fullcalendar.min.js', __FILE__), array('jquery'));
            wp_enqueue_style('full-calendar-css',plugins_url('/menu-pages/full-calendar-assets/fullcalendar.css', __FILE__));

            //class booking bootstrap custom css
            wp_enqueue_style('apcal-bootstrap-apcal',plugins_url('/menu-pages/bootstrap-assets/css/bootstrap-apcal.css', __FILE__));

            //font-awesome css
            wp_enqueue_style( 'font-awesome-css', plugins_url('/menu-pages/font-awesome-assets/css/font-awesome.css', __FILE__) );

            //jet-pack tweak remove open graph tag if jet-pack plugin activated
            remove_action('wp_head', 'jetpack_og_tags');
            break;
        }
    }
}
add_action( 'wp', 'shortcode_detect' );



//Date & Time Format Settings
$DateFormat = get_option('apcal_date_format');
if($DateFormat == '') $DateFormat = "d-m-Y";
$TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $TimeFormat = "h:i";


//Rendering All appointment-calendar Menu Page


//admin booking page
function display_admin_class_booking_page() {
    require_once("menu-pages/admin-class-booking.php");
}

// classes page
function display_classes_page() {
    require_once("menu-pages/classes.php");
}

//client page
function display_class_clients_page() {
    require_once("menu-pages/class-clients.php");
}

//manage booking page
function display_manage_class_bookings_page() {
    require_once("menu-pages/manage-bookings.php");
}

//settings page
function display_class_booking_settings_page() {
    require_once("menu-pages/settings.php");
}

// Uninstall plugin
function display_uninstall_plugin_page() {
    require_once("uninstall-plugin.php");
}

// Support & Help
function display_help_n_support_page() {
    require_once("menu-pages/help-n-support.php");
}

//Including Class Booking Short-code
require_once("class-booking-shortcode.php");