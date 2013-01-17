<?php

class CPAC_Licence {

    /**
     * The type of licence to check or activate
     *
	 * @since 1.3.0
	 * 
     * @var string $type License Type
     */
    private $type;

	/**
	 * Constructor
	 *
	 * @since 1.3.0
	 */
    public function __construct( $type ) {
        $this->type = $type;
    }

    /**
	 * Unlocks
	 *
	 * @since 1.3.0
	 *
	 * @return bool
	 */
	public function is_unlocked() {
		return preg_match( '/^[a-f0-9]{40}$/i', $this->get_license_key( $this->type ) );
	}

	/**
	 * Check license key with API
	 *
	 * @since 1.3.3
	 *
	 * @param string MD5 Key
	 * @return bool
	 */
	public function check_remote_key( $key ) {
		if ( empty( $key ) ) {
			return false;
        }

		// check key with remote API
 		$response = wp_remote_post( 'http://www.codepress.nl/', array(
			'body'	=> array(
				'api'	=> 'addon',
				'key'	=> $key,
				'type'	=> $this->type
			)
		));

		// license will be valid in case of WP error or succes
		if ( is_wp_error( $response ) || ( isset( $response['body'] ) && json_decode( $response['body'] ) == 'valid' ) ) {
			return true;
        }

		return false;
	}

	/**
	 * Set masked license key
	 *
	 * @since 1.3.1
	 *
	 * @param string Masked Key
	 */
	public function get_masked_license_key() {
		return '**************************'.substr( $this->get_license_key(), -4 );
	}

	/**
	 * Get license key
	 *
	 * @since 1.3.0
	 *
	 * @param string Stored Key.
	 */
	public function get_license_key() {
		return get_option("cpac_{$this->type}_ac");
	}

	/**
	 * Set license key
	 *
	 * @since 1.3.0
	 *
	 * @param string Key.
	 */
	public function set_license_key( $key ) {
		update_option( "cpac_{$this->type}_ac", trim( $key ) );
	}

	/**
	 * Remove license key
	 *
	 * @since 1.3.1
	 */
	public function remove_license_key() {
		delete_option( "cpac_{$this->type}_ac" );
		delete_transient("cpac_{$this->type}_trnsnt");
	}
}