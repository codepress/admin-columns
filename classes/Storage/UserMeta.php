<?php

namespace AC\Storage;

class UserMeta
	implements KeyValuePair {

	/**
	 * @var int
	 */
	protected $user_id;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @param int    $user_id
	 * @param string $key
	 */
	public function __construct( $key, $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! preg_match( '/^[1-9][0-9]*$/', $user_id ) ) {
			throw new \Exception( 'Storage cannot be initialized without a valid user id.' );
		}

		$this->user_id = $user_id;
		$this->key = $key;
	}

	/**
	 * @return mixed
	 */
	public function get() {
		return get_user_meta( $this->user_id, $this->key, true );
	}

	/**
	 * @param $value
	 *
	 * @return bool|int
	 */
	public function save( $value ) {
		return update_user_meta( $this->user_id, $this->key, $value );
	}

	/**
	 * @return bool
	 */
	public function delete() {
		return delete_user_meta( $this->user_id, $this->key );
	}

}