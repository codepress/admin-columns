<?php

class AC_Preferences {

	/**
	 * @var int
	 */
	private $user_id;

	/**
	 * Preferences of this user
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * The label for this set of preferences
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * @param string   $label
	 * @param null|int $user_id
	 */
	public function __construct( $label, $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$this->user_id = intval( $user_id );
		$this->label = sanitize_key( $label );
		$this->data = $this->load();
	}

	/**
	 * Return the key used to store and retrieve this preference
	 *
	 * @return string
	 */
	private function get_key() {
		return 'ac_preferences_' . $this->label;
	}

	/**
	 * @return array|string
	 */
	private function load() {
		return get_user_option( $this->get_key(), $this->user_id );
	}

	/**
	 * @return bool
	 */
	private function save() {
		return (bool) update_user_option( $this->user_id, $this->get_key(), $this->data );
	}

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return false;
		}

		return $this->data[ $key ];
	}

	/**
	 * @param string $key
	 * @param mixed  $data
	 *
	 * @return bool
	 */
	public function set( $key, $data ) {
		$this->data[ $key ] = $data;

		return $this->save();
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete( $key ) {
		unset( $this->data[ $key ] );

		return $this->save();
	}

	/**
	 * Reset all preferences for all users that match on the current label
	 */
	public function reset_for_all_users() {
		global $wpdb;

		$sql = "
			DELETE 
			FROM {$wpdb->usermeta} 
			WHERE meta_key LIKE %s
		";

		return (bool) $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( $this->get_key() ) . '%' ) );
	}

}