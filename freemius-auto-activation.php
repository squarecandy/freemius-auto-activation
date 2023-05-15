<?php
/*
Plugin Name: Freemius Auto Activation
Plugin URI: https://github.com/squarecandy/freemius-auto-activation
GitHub Plugin URI: https://github.com/squarecandy/freemius-auto-activation
Primary Branch: main
Description: Enables entering Freemius plugin activation codes via wp-config.php
Version: 1.2.5
Author: vovafeldman, squarecandy

Based off of this gist:
https://gist.github.com/vovafeldman/f28a46958d8f648cf3f62c7a3a975a8e


Copyright 2021 Peter T. Wise D.B.A. Square Candy Design

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

add_action( 'plugins_loaded', 'squarecandy_freemius_autoactivate' );
function squarecandy_freemius_autoactivate() {

	if ( ! class_exists( 'Freemius_License_Auto_Activator' ) ) {
		require_once plugin_dir_path( __FILE__ ) . 'class-freemius-license-auto-activator.php';
	}

	if ( defined( 'FS_SHORTCODES' ) && ! empty( FS_SHORTCODES ) ) :
		if ( ! is_array( FS_SHORTCODES ) ) {
			$fs_shortcodes = array( FS_SHORTCODES );
		} else {
			$fs_shortcodes = FS_SHORTCODES;
		}
		foreach ( $fs_shortcodes as $fs_shortcode ) :
			if ( defined( 'WP__' . strtoupper( $fs_shortcode ) . '__LICENSE_KEY' ) ) {
				$fs_license_activator = new Freemius_License_Auto_Activator( $fs_shortcode );
			}
		endforeach;
	endif;
}
