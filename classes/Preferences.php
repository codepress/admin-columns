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
	abstract public function save(): bool;

	public function __construct( string $label, int $user_id = null ) {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		$this->user_id = $user_id;
		$this->label = sanitize_key( $label );

		$data = $this->load();

		if ( is_array( $data ) ) {
			foreach ( $data as $k => $v ) {
				$this->set( $k, $v, false );
			}
		}
	}

	protected function get_key(): string {
		return 'ac_preferences_' . $this->label;
	}

	protected function get_user_id(): int {
		return $this->user_id;
	}

	public function exists( $key ): bool {
		return null !== $this->get( $key );
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get( string $key ) {
		return $this->data[ $key ] ?? null;
	}

	/**
	 * @param string $key
	 * @param mixed  $data
	 * @param bool   $save Immediately save changes to database
	 *
	 * @return bool
	 */
	public function set( string $key, $data, bool $save = true ): bool {
		$this->data[ $key ] = $data;

		if ( $save ) {
			return $this->save();
		}

		return true;
	}

	public function delete( string $key, bool $save = true ): bool {
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
	public function reset_for_all_users(): bool {
		if ( empty( $this->label ) ) {
			return false;
		}

		global $wpdb;

		$sql = "
			DELETE 
			FROM $wpdb->usermeta 
			WHERE meta_key LIKE %s
		";

		$sql = $wpdb->prepare(
			$sql,
			$wpdb->esc_like( $wpdb->get_blog_prefix() . $this->get_key() ) . '%'
		);

		return (bool) $wpdb->query( $sql );
	}

}