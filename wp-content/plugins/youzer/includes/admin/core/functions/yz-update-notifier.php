<?php
/**************************************************************
 *                                                            *
 *   Provides a notification to the user everytime            *
 *   your WordPress plugin is updated                         *
 *															  *
 *	 Based on the script by Unisphere:						  *
 *   https://github.com/unisphere/unisphere_notifier          *
 *                                                            *
 *   Author: Pippin Williamson                                *
 *   Profile: http://codecanyon.net/user/mordauk              *
 *   Follow me: http://twitter.com/pippinsplugins             *
 *                                                            *
 **************************************************************/
 
/*
	Replace XXX and xxx by your plugin prefix to prevent conflicts between plugins using this script.
*/

// Define Vars.
define( 'YOUZER_NOTIFIER_PLUGIN_NAME', 'Youzer' );
define( 'YOUZER_PLUGIN_NOTIFIER_CACHE_INTERVAL', DAY_IN_SECONDS );
define( 'YOUZER_NOTIFIER_PLUGIN_SHORT_NAME', 'YOUZER' );
define( 'YOUZER_NOTIFIER_PLUGIN_FOLDER_NAME', 'youzer' );
define( 'YOUZER_NOTIFIER_PLUGIN_FILE_NAME', 'youzer.php' );
define( 'YOUZER_NOTIFIER_PLUGIN_XML_FILE', 'http://youzer.kainelabs.com/notifier.xml' );
define( 'YOUZER_PLUGIN_NOTIFIER_CODECANYON_USERNAME', 'kainelabs' );

/**
 * Get Plugin Last Version.
 **/
function YOUZER_get_plugin_last_version() {
	// Get The version of the plugin from XML.
	$xml = YOUZER_get_latest_plugin_version(); // Get the latest remote XML file on our server
	return (string) $xml->latest;
}

/**
 * Get Installed Plugin Last Version.
 **/
function YOUZER_get_installed_plugin_version() {
	
	// Get Plugin Data.	
	$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . YOUZER_NOTIFIER_PLUGIN_FOLDER_NAME . '/' . YOUZER_NOTIFIER_PLUGIN_FILE_NAME );

	// Get Plugin Version.
	$plugin_version = $plugin_data['Version'];

	if ( isset( $plugin_data['Version'] ) && empty( $plugin_data['Version'] ) ) {
		$plugin_version = YZ_Version;
	}

	return (string) $plugin_version;
	
}

/**
 * Check if there's a new update.
 */
function YOUZER_new_update_is_available() {

	// Get Data.
	$plugin_last_version = YOUZER_get_plugin_last_version();
	$installed_plugin_version = YOUZER_get_installed_plugin_version();

	if ( $plugin_last_version > $installed_plugin_version ) {
		return true;
	}

	return false;
}

// Adds an update notification to the WordPress Dashboard menu
function YOUZER_update_plugin_notifier_menu() {  
	
	if ( ! function_exists( 'simplexml_load_string' ) || ! YOUZER_new_update_is_available() ) {
		return;
	}

	// Get Menu Name.
	$menu_name = defined( 'YOUZER_NOTIFIER_PLUGIN_SHORT_NAME' ) ? YOUZER_NOTIFIER_PLUGIN_SHORT_NAME : YOUZER_NOTIFIER_PLUGIN_NAME;
	add_dashboard_page( YOUZER_NOTIFIER_PLUGIN_NAME . ' Plugin Updates', $menu_name . ' <span class="update-plugins count-1"><span class="update-count">New Updates</span></span>', 'administrator', 'youzer-plugin-update-notifier', 'YOUZER_update_notifier');
}

// add_action('admin_menu', 'YOUZER_update_plugin_notifier_menu');  



// Adds an update notification to the WordPress 3.1+ Admin Bar
function YOUZER_update_notifier_bar_menu() {

	if ( ! function_exists( 'simplexml_load_string' ) || ! YOUZER_new_update_is_available() ) {
		return;
	}

	// Don't display notification in admin bar if it's disabled or the current user isn't an administrator
	if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
		return;
	}

	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array( 'id' => 'plugin_update_notifier', 'title' => '<span>' . YOUZER_NOTIFIER_PLUGIN_NAME . ' <span id="ab-updates">New Updates</span></span>', 'href' => get_admin_url() . 'index.php?page=youzer-plugin-update-notifier' ) );
}

// add_action( 'admin_bar_menu', 'YOUZER_update_notifier_bar_menu', 1000 );

/**
 * Youzer Notifier Page.
 **/
function YOUZER_update_notifier() { 
	$latest_version = YOUZER_get_latest_plugin_version(); // Get the latest remote XML file on our server
	$installed_version 	= YOUZER_get_installed_plugin_version(); // Read plugin current version from the main plugin file ?>

	<style>
		.update-nag { display: none; }
		#instructions {max-width: 670px;}
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">

		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo YOUZER_NOTIFIER_PLUGIN_NAME ?> Plugin Updates</h2>
	    <div id="message" class="updated below-h2"><p><strong>There is a new version of the <?php echo YOUZER_NOTIFIER_PLUGIN_NAME; ?> plugin available.</strong> You have version <?php echo $installed_version; ?> installed. Update to version <?php echo $latest_version; ?>.</p></div>
		
		<div id="instructions">
		    <h3>Update Download and Instructions</h3>
		    <p><strong>Please note:</strong> make a <strong>backup</strong> of the Plugin inside your WordPress installation folder <strong>/wp-content/plugins/<?php echo YOUZER_NOTIFIER_PLUGIN_FOLDER_NAME; ?>/</strong></p>
		    <p><strong># Update Method 1 ( Recommended ) :</strong></p>
		    <p>Use the <a href="https://envato.com/market-plugin/">Envato Market</a> plugin to update to the last version.</p>
		    <p><strong># Update Method 2 :</strong></p>
		    <p>To update the Plugin, login to <a href="http://www.codecanyon.net/?ref=<?php echo YOUZER_PLUGIN_NOTIFIER_CODECANYON_USERNAME; ?>">CodeCanyon</a>, head over to your <strong>downloads</strong> section and re-download the plugin like you did when you bought it.</p>
		    <p>Extract the zip's contents, look for the extracted plugin folder, and after you have all the new files upload them using FTP to the <strong>/wp-content/plugins/<?php echo YOUZER_NOTIFIER_PLUGIN_FOLDER_NAME; ?>/</strong> folder overwriting the old ones (this is why it's important to backup any changes you've made to the plugin files).</p>
		    <p>If you didn't make any changes to the plugin files, you are free to overwrite them with the new ones without the risk of losing any plugins settings, and backwards compatibility is guaranteed.</p>
		</div>
	    
	    <h3 class="title">Changelog</h3>
	    <?php echo $latest_version->changelog; ?>

	</div>
    
<?php } 



// Get the remote XML file contents and return its data (Version and Changelog)
// Uses the cached version if available and inside the time interval defined
function YOUZER_get_latest_plugin_version() {
	$interval = YOUZER_PLUGIN_NOTIFIER_CACHE_INTERVAL;
	$notifier_file_url = YOUZER_NOTIFIER_PLUGIN_XML_FILE;	
	$db_cache_field = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( ! $last || ( ( $now - $last ) > $interval ) ) {
		// cache doesn't exist, or is old, so refresh it
		if( function_exists( 'curl_init' ) ) { // if cURL is available, use it...
			$ch = curl_init( $notifier_file_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
			$cache = curl_exec( $ch );
			curl_close( $ch );
		} else {
			$cache = file_get_contents( $notifier_file_url ); // ...if not, use the common file_get_contents()
		}

		if ( $cache ) {			
			// we got good results	
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		} 
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}

	// Let's see if the $xml data was returned as we expected it to.
	// If it didn't, use the default 1.0 as the latest version so that we don't have problems when the remote server hosting the XML file is down
	if( strpos( (string) $notifier_data, '<notifier>' ) === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}

	// Load the remote XML data into a variable and return it
	$xml = simplexml_load_string( $notifier_data ); 

	return $xml;
}
