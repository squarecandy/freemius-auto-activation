<?php
class Freemius_License_Auto_Activator {
	private $priv_shortcode;

	public function __construct( $shortcode ) {
		$this->priv_shortcode = $shortcode;
		add_action( 'admin_init', array( $this, 'license_key_auto_activation', 999 ) );
	}

	public function license_key_auto_activation() {

		$fs = false;

		if ( function_exists( $this->priv_shortcode ) ) {
			$fs = ( $this->priv_shortcode )();
		} else {
			global ${$this->priv_shortcode};
			$fs = ${$this->priv_shortcode};
		}

		if ( empty( $fs ) ) {
			return;
		}

		if ( ! $fs->has_api_connectivity() || $fs->is_registered() ) {
			// No connectivity OR the user already opted-in to Freemius.
			return;
		}

		$option_key = "{$this->priv_shortcode}_auto_license_activation";

		if ( 'pending' !== get_option( $option_key, 'pending' ) ) {
			return;
		}

		try {
			$key       = constant( 'WP__' . strtoupper( $this->priv_shortcode ) . '__LICENSE_KEY' );
			$next_page = $fs->activate_migrated_license( $key );
		} catch ( Exception $e ) {
			update_option( $option_key, 'unexpected_error' );
			return;
		}

		if ( $fs->can_use_premium_code() ) {
			update_option( $option_key, 'done' );

			if ( is_string( $next_page ) ) {
				fs_redirect( $next_page );
			}
		} else {
			update_option( $option_key, 'failed' );
		}
	}
}
