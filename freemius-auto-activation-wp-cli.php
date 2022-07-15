<?php
define( 'FREEMIUS_WP_CLI', true );

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
			echo "\n==========\nattempting auto activation for " . $fs_shortcode . "\n";
			$fs_license_activator->license_key_auto_activation();
		}
	endforeach;
endif;
