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
		$this->debug_notices( 'license_key_auto_activation function started' );

		if ( function_exists( $this->priv_shortcode ) ) {
			$fs = ( $this->priv_shortcode )();
		} else {
			global ${$this->priv_shortcode};
			$fs = ${$this->priv_shortcode};
		}

		if ( empty( $fs ) ) {
			$this->debug_notices( 'fs is empty ' );
			return;
		}

		if ( false === $fs->has_api_connectivity() ) {
			$this->debug_notices( 'Error: no API connectivity' );
			return;
		}

		if ( $fs->is_registered() ) {
			$this->debug_notices( 'Notice: The user already opted-in to Freemius' );
		}

		$option_key = "{$this->priv_shortcode}_auto_license_activation";
		$this->debug_notices( "$option_key " );

		try {
			$key       = constant( 'WP__' . strtoupper( $this->priv_shortcode ) . '__LICENSE_KEY' );
			$next_page = $fs->activate_migrated_license( $key );
		} catch ( Exception $e ) {
			$this->debug_notices( 'Error: ' . $e->getMessage() );
			update_option( $option_key, 'unexpected_error' );
			return;
		}

		if ( $fs->can_use_premium_code() ) {
			update_option( $option_key, 'done' );
			$this->debug_notices( 'Success: license key install is done.' );

			if ( is_string( $next_page ) ) {
				fs_redirect( $next_page );
			}
		} else {
			$this->debug_notices( 'Error: license key install failed ' );
			update_option( $option_key, 'failed' );
		}
	}

	private function debug_notices( $message ) {
		if ( WP_DEBUG ) {
			error_log( $message ); // phpcs:ignore
		}
		if ( defined( 'FREEMIUS_WP_CLI' ) && FREEMIUS_WP_CLI ) {
			echo "$message \n";
		}
	}
}
