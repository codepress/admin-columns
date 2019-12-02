<?php

namespace AC\Plugin\Update;

use AC\ListScreenRepository\DataBase;
use AC\ListScreenTypes;
use AC\Plugin\Update;
use AC\Storage\ListScreenOrder;

class V4000 extends Update {

	/** @var DataBase */
	private $repository;

	/** @var ListScreenTypes */
	private $list_screen_types;

	public function __construct( $stored_version ) {
		$this->list_screen_types = ListScreenTypes::instance();
		$this->repository = new DataBase( $this->list_screen_types );

		parent::__construct( $stored_version );
	}

	public function apply_update() {
		$this->migrate_list_screen_settings();
		$this->migrate_list_screen_order();
	}

	protected function set_version() {
		$this->version = '4.0.0';
	}

	/**
	 * Migrate the order of the list screens from the `usermeta` to the `options` table
	 */
	private function migrate_list_screen_order() {
		global $wpdb;

		// Check the first usermeta we can find
		$sql = "
			SELECT `meta_value` FROM {$wpdb->prefix}usermeta
			WHERE `meta_key` = '{$wpdb->prefix}ac_preferences_layout_order'
				AND `meta_value` != ''
			LIMIT 1
		";

		$result = $wpdb->get_var( $sql );

		if ( ! $result ) {
			return;
		}

		$list_screens = maybe_unserialize( $result );

		if ( ! $list_screens ) {
			return;
		}

		$order = new ListScreenOrder();

		foreach ( $list_screens as $list_screen_key => $ids ) {
			$order->set( $list_screen_key, $ids );
		}
	}

	private function migrate_list_screen_settings() {

		// 1. fetch all list screen settings
		global $wpdb;

		$sql = "
			SELECT *
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
		";

		$results = $wpdb->get_results( $sql );

		if ( ! is_array( $results ) ) {
			return;
		}

		// 2. convert to post data
		foreach ( $results as $row ) {
			// Ignore settings that contain the default columns
			if ( $this->has_suffix( "__default", $row->option_name ) ) {
				continue;
			}

			$columns = maybe_unserialize( $row->option_value );

			// A layout without columns can be valid
			if ( empty( $columns ) ) {
				$columns = [];
			}

			$storage_key = $this->remove_prefix( 'cpac_options_', $row->option_name );

			// Truly eliminates a duplicate $unique_id, because `uniqid()` is based on the current time in microseconds
			usleep( 1000 );

			// Defaults
			$list_data = [
				'id'       => uniqid( 'ac' ),
				'title'    => __( 'Original', 'codepress-admin-columns' ),
				'key'      => $storage_key,
				'columns'  => $columns,
				'settings' => [],
			];

			$layout_settings = $this->get_layout_settings( $storage_key );

			if ( $layout_settings ) {

				// Add layout settings
				if ( $layout_settings->id ) {
					$list_data['id'] = $layout_settings->id;
					$list_data['key'] = $this->remove_suffix( $layout_settings->id, $storage_key );
				}
				if ( $layout_settings->name ) {
					$list_data['title'] = $layout_settings->name;
				}
				if ( $layout_settings->users ) {
					$list_data['settings']['users'] = $layout_settings->users;
				}
				if ( $layout_settings->roles ) {
					$list_data['settings']['roles'] = $layout_settings->roles;
				}
			} else {

				// A list screen without layouts ánd without columns is invalid
				if ( empty( $columns ) ) {
					continue;
				}

				// A list screen without layouts and with a layout ID is invalid
				$id = $this->contains_layout_id( $storage_key );

				if ( $id ) {
					continue;
				}
			}

			$list_screen = $this->list_screen_types->get_list_screen_by_key( $list_data['key'] );

			if ( ! $list_screen ) {
				continue;
			}

			$list_screen->set_layout_id( $list_data['id'] )
			            ->set_title( $list_data['title'] )
			            ->set_settings( $list_data['columns'] )
			            ->set_preferences( $list_data['settings'] );

			$this->repository->save( $list_screen );

			// cleanup
			// todo
//			$wpdb->delete( $wpdb->options, [ 'option_id' => $row->option_id ] );
		}
	}

	/**
	 * @param string $storage_key
	 *
	 * @return string|false ID
	 */
	private function contains_layout_id( $storage_key ) {
		$prefixes = [
			'wp-users',
			'wp-media',
			'wp-comments',
			'wp-ms_sites',
			'wp-ms_users',
		];

		foreach ( $prefixes as $prefix ) {
			if ( $this->has_prefix( $prefix, $storage_key ) ) {
				$layout_id = $this->remove_prefix( $prefix, $storage_key );
				$layout_id = substr( $layout_id, -13 );

				return $this->is_layout_id( $layout_id );
			}
		}

		// Is it a taxonomy?
		if ( $this->has_prefix( 'wp-taxonomy_', $storage_key ) ) {
			$taxonomy = $this->remove_prefix( 'wp-taxonomy_', $storage_key );

			if ( taxonomy_exists( $taxonomy ) ) {
				return false;
			}

			$layout_id = substr( $taxonomy, -13 );

			return $this->is_layout_id( $layout_id );
		}

		// is it a post?
		if ( post_type_exists( $storage_key ) ) {
			return false;
		}

		$layout_id = substr( $storage_key, -13 );

		// todo: test with ninja forms add-on

		return $this->is_layout_id( $layout_id );
	}

	/**
	 * @param string $id
	 *
	 * @return string|false
	 */
	private function is_layout_id( $id ) {
		return $id && is_string( $id ) && ( strlen( $id ) === 13 ) && $this->is_hex( $id ) ? $id : false;
	}

	/**
	 * @param string $string
	 *
	 * @return bool
	 */
	private function is_hex( $string ) {
		return '' == trim( substr( $string, -13 ), '0..9A..Fa..f' );
	}

	private function remove_prefix( $prefix, $string ) {
		return (string) preg_replace( '/^' . preg_quote( $prefix, '/' ) . '/', '', $string );
	}

	/**
	 * @param string $string
	 * @param string $end
	 *
	 * @return string
	 */
	private function remove_suffix( $suffix, $string ) {
		return (string) preg_replace( '/' . preg_quote( $suffix, '/' ) . '$/', '', $string );
	}

	/**
	 * @param string $prefix
	 * @param string $string
	 *
	 * @return bool
	 */
	private function has_prefix( $prefix, $string ) {
		return substr( $string, 0, strlen( $prefix ) ) === $prefix;
	}

	/**
	 * @param string $suffix
	 * @param string $string
	 *
	 * @return bool
	 */
	private function has_suffix( $suffix, $string ) {
		return substr( $string, strlen( $suffix ) * -1 ) === $suffix;
	}

	/**
	 * @param string $storage_key
	 *
	 * @return object
	 */
	private function get_layout_settings( $storage_key ) {
		return get_option( 'cpac_layouts' . $storage_key );
	}

}