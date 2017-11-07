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
	protected $data = array();

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
		$this->label = sanitize_key( (string) $label );
		$this->load();
	}

	/**
	 * Return the key used to store and retrieve this preference
	 *
	 * @return string
	 */
	private function get_key() {
		return 'ac_preferences_' . $this->label;
	}

	private function load() {
		$data = get_user_option( $this->get_key(), $this->user_id );

		if ( is_array( $data ) ) {
			foreach ( $data as $k => $v ) {
				$this->set( $k, $v, false );
			}
		}
	}

	/**
	 * @return bool
	 */
	public function save() {
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
	 * @param bool   $save Immediately save changes to database
	 *
	 * @return bool
	 */
	public function set( $key, $data, $save = true ) {
		$this->data[ $key ] = $data;

		if ( $save ) {
			return $this->save();
		}

		return true;
	}

	/**
	 * @param string $key
	 * @param bool   $save Immediately save changes to database
	 *
	 * @return bool
	 */
	public function delete( $key, $save = true ) {
		if ( ! $this->get( $key ) ) {
			return false;
		}

		unset( $this->data[ $key ] );

		if ( $save ) {
			return $this->save();
		}

		return true;
	}

	/**
	 * Reset all preferences for all users that match on the current label
	 */
	public function reset_for_all_users() {
		if ( empty( $this->label ) ) {
			return false;
		}

		global $wpdb;

		$sql = "
			DELETE 
			FROM {$wpdb->usermeta} 
			WHERE meta_key LIKE %s
		";

		return (bool) $wpdb->query( $wpdb->prepare( $sql, $wpdb->esc_like( $this->get_key() ) . '%' ) );
	}

}