<?php 
/*
Plugin Name: WP Super Auction
Plugin URI:  http://plugins.hire-expert-developer.com/wp-super-auction/
Description: This plugin is usefull for adding a auction functionality on a post type.
Version:     1.0
Author:      Shrikant Yadav
Author URI:  http://shrikant-y.hire-expert-developer.com/
License:     GPL2
 
WP Super Auction is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP Super Auction is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WP Super Auction. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

global $wpsap_version;
$wpsap_version = '1.0';


define( 'WPSAP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/*
* Including files
*/
require_once ( WPSAP_PLUGIN_PATH . 'includes/wpsap-admin-settings.php' );
require_once ( WPSAP_PLUGIN_PATH . 'includes/wpsap-frontend-functions.php' );
require_once ( WPSAP_PLUGIN_PATH . 'includes/wpsap-functions.php' );
require_once ( WPSAP_PLUGIN_PATH . 'includes/wpsap-shortcodes.php' );



/*
 * Plugin Setting Function
 */
function wpsap_settings() {
    global $wpsap_version;
    
    // Plugin Setting Code
    add_option('wpsap_version',$wpsap_version);

}
add_action('init','wpsap_settings');

/*
 * Plugin Remove Setting Function 
 */
function wpsap_remove_setting() { 
    
    // Delete version
    delete_option('wpsap_version',$wpsap_version);
}

/*
 * Remove Plugin Settings and Options 
 */
function wpsap_remove_settings_and_options() {
    // Plugin remove setting and option code
    
}

/*
* Plugin Activation Function
*/ 
function wpsap_activation() {
  
    // Plugin setting function
    wpsap_settings();

    // Clear the permalinks
    flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'wpsap_activation' );


/*
 * Plugin Deactivation Function
 */
function wpsap_deactivation() {
    
    // Deactivation rules here
    wpsap_remove_setting();
    
    // Clear the permalinks
    flush_rewrite_rules();

}
register_deactivation_hook( __FILE__, 'wpsap_deactivation' );

/*
* Front End Styles and Scripts 
*/
function wpsap_wp_enqueue_scripts() {

        wp_enqueue_style( 'wpsap-style', plugins_url( 'css/wpsap-style.css', __FILE__ ) );

        wp_enqueue_script( 'wpsap-ajax-js', plugins_url( 'js/wpsap-ajax.js', __FILE__ ), array( 'jquery'), '20160520', true );

        wp_localize_script( 'wpsap-ajax-js', 'LOCOBJ', array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'wpsap_security' => wp_create_nonce( 'wpsap_setting_nonce_action' ),
             ));
        
        wp_enqueue_script( 'wpsap-scripts-js', plugins_url( 'js/wpsap-script.js', __FILE__ ), array( 'jquery'), '20160520', true );

}
add_action( 'wp_enqueue_scripts', 'wpsap_wp_enqueue_scripts' );


/*
* Admin Script and Styles
*/
function wpsap_admin_enqueue_scripts() {

        global $pagenow, $typenow;
        wp_enqueue_style( 'wpsap-admin-style', plugins_url( 'css/wpsap-admin-style.css', __FILE__ ) );
        wp_enqueue_script( 'wpsap-admin-scripts', plugins_url( 'js/wpsap-admin-scripts.js', __FILE__ ), array( 'jquery'), '20160520', true );
        
}
add_action( 'admin_enqueue_scripts', 'wpsap_wp_enqueue_scripts' ); 