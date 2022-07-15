<?php
/* phpcs:disable Generic.ControlStructures.InlineControlStructure.NotAllowed */
class Freemius_License_Auto_Activator {
	private $priv_shortcode;

	public function __construct( $shortcode ) {
		$this->priv_shortcode = $shortcode;
		add_action( 'admin_init', array( $this, 'license_key_auto_activation' ), 999 );
	}

	public function license_key_auto_activation() {

		$fs = false;
		if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " license_key_auto_activation function started\n";

		if ( function_exists( $this->priv_shortcode ) ) {
			$fs = ( $this->priv_shortcode )();
		} else {
			global ${$this->priv_shortcode};
			$fs = ${$this->priv_shortcode};
		}

		if ( empty( $fs ) ) {
			if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " fs is empty \n";
			return;
		}

		if ( ! $fs->has_api_connectivity() ) {
			if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " Error: no API connectivity\n";
			return;
		}

		if ( $fs->is_registered() ) {
			if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " Notice: The user already opted-in to Freemius\n";
		}

		$option_key = "{$this->priv_shortcode}_auto_license_activation";
		if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " $option_key \n";

		try {
			$key       = constant( 'WP__' . strtoupper( $this->priv_shortcode ) . '__LICENSE_KEY' );
			$next_page = $fs->activate_migrated_license( $key );
		} catch ( Exception $e ) {
			if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " Error: $e->message \n";
			update_option( $option_key, 'unexpected_error' );
			return;
		}

		if ( $fs->can_use_premium_code() ) {
			update_option( $option_key, 'done' );
			if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " Success: license key install is done.\n";

			if ( is_string( $next_page ) ) {
				fs_redirect( $next_page );
			}
		} else {
			if ( WP_DEBUG || FREEMIUS_WP_CLI ) echo __LINE__ . " Error: license key install failed \n";
			update_option( $option_key, 'failed' );
		}
	}
}
