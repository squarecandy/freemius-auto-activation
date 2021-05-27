<?php
/*
Plugin Name: Freemius Auto Activation
Plugin URI: https://github.com/squarecandy/freemius-auto-activation
GitHub Plugin URI: https://github.com/squarecandy/freemius-auto-activation
Description: Enables entering Freemius plugin activation codes via wp-config.php
Version: 1.0.0
Author: vovafeldman, squarecandy

Based off of this gist:
https://gist.github.com/vovafeldman/f28a46958d8f648cf3f62c7a3a975a8e

*/

add_action( 'plugins_loaded', 'squarecandy_freemius_autoactivate' );
function squarecandy_freemius_autoactivate() {

	if ( ! class_exists( 'Freemius_License_Auto_Activator' ) ) {
		require_once plugin_dir_path( __FILE__ ) . 'class-freemius-license-auto-activator.php';
	}

	if ( defined( 'FS_SHORTCODES' ) && is_array( FS_SHORTCODES ) ) :
		foreach ( FS_SHORTCODES as $fs_shortcode ) :
			if ( defined( 'WP__' . strtoupper( $fs_shortcode ) . '__LICENSE_KEY' ) ) {
				$fs_license_activator = new Freemius_License_Auto_Activator( $fs_shortcode );
			}
		endforeach;
	endif;
}






