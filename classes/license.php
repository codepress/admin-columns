<?php

class cpac_licence
{
    /**
     * The type of licence to check or activate
     *
     * @var string $type
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
	 * Unlocks
	 *
	 * @since 1.3
	 */
	public function is_unlocked()
	{
		return preg_match( '/^[a-f0-9]{40}$/i', $this->get_license_key( $this->type ) );
	}

	/**
	 * Check license key with API
	 *
	 * @since 1.3.3
	 */
	public function check_remote_key( $key )
	{
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
		if ( is_wp_error($response) || ( isset($response['body']) && json_decode($response['body']) == 'valid' ) ) {
			return true;
        }

		return false;
	}

	/**
	 * Set masked license key
	 *
	 * @since 1.3.1
	 */
	public function get_masked_license_key()
	{
		return '**************************'.substr( $this->get_license_key(), -4 );
	}

	/**
	 * Get license key
	 *
	 * @since 1.3
	 */
	public function get_license_key()
	{
		return get_option("cpac_{$this->type}_ac");
	}

	/**
	 * Set license key
	 *
	 * @since 1.3
	 */
	public function set_license_key( $key )
	{
		update_option( "cpac_{$this->type}_ac", trim( $key ) );
	}

	/**
	 * Remove license key
	 *
	 * @since 1.3.1
	 */
	public function remove_license_key()
	{
		delete_option( "cpac_{$this->type}_ac" );
		delete_transient("cpac_{$this->type}_trnsnt");
	}
}