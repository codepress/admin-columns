<?php

namespace AC;

abstract class Preferences {

	/**
	 * @var int
	 */
	private $user_id;

	/**
	 * The label for this set of preferences
	 * @var string
	 */
	private $label;

	/**
	 * Preferences of this user
	 * @var array
	 */
	protected $data = [];

	/**
	 * Retrieves data from DB
	 * return array|false
	 */
	abstract protected function load();

	/**
	 * Stores data to DB
	 * @return bool
	 */
	abstract public function save();

	/**
	 * @param string   $label
	 * @param null|int $user_id
	 */
	public function __construct( $label, $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$this->user_id = (int) $user_id;
		$this->label = sanitize_key( (string) $label );

		$data = $this->load();

		if ( is_array( $data ) ) {
			foreach ( $data as $k => $v ) {
				$this->set( $k, $v, false );
			}
		}
	}

	/**
	 * Return the key used to store and retrieve this preference
	 * @return string
	 */
	protected function get_key() {
		return 'ac_preferences_' . $this->label;
	}

	/**
	 * @return int
	 */
	protected function get_user_id() {
		return $this->user_id;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return null;
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
		if ( null === $this->get( $key ) ) {
			return false;
		}

		unset( $this->data[ $key ] );

		if ( $save ) {
			return $this->save();
		}

		return true;
	}

	/**
	 * Reset site preferences for all users that match on the current label
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

		$sql = $wpdb->prepare( $sql, $wpdb->esc_like( $wpdb->get_blog_prefix() . $this->get_key() ) . '%' );

		return (bool) $wpdb->query( $sql );
	}

}