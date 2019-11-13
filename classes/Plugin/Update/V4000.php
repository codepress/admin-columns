<?php

namespace AC\Plugin\Update;

use AC\ListScreenRepository\DataBase;
use AC\ListScreenTypes;
use AC\Plugin\Update;
use AC\PostTypes;

// todo
class V4000 extends Update {

	/** @var DataBase */
	private $data_base_repository;

	public function __construct( $stored_version ) {
		$this->data_base_repository = new DataBase( ListScreenTypes::instance() );

		parent::__construct( $stored_version );
	}

	public function apply_update() {
		$this->migrate_list_screen_settings();
	}

	protected function set_version() {
		$this->version = '4.0.0';
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

		$settings = [];

		foreach ( $results as $row ) {
			// Ignore settings that contain the default columns
			if ( "__default" === substr( $row->option_name, -9 ) ) {
				continue;
			}

			$settings[] = (object) [
				'option_id' => $row->option_id,
				'name'      => $this->remove_prefix( 'cpac_options_', $row->option_name ),
				'columns'   => maybe_unserialize( $row->option_value ),
			];
		}

		$posts_data = [];

		// 2. convert to post data
		foreach ( $settings as $setting ) {
			if ( ! $setting->columns ) {
				continue;
			}

			$storage_key = $setting->name;

			// Truly eliminates a duplicate $unique_id, because `uniqid()` is based on the current time in microseconds
			usleep( 1000 );

			$unique_id = uniqid( 'ac' );

			// Defaults
			$post_data = [
				'post_status' => 'publish',
				'post_type'   => PostTypes::LIST_SCREEN_DATA,
				'post_title'  => __( 'Original', 'codepress-admin-columns' ),
				'meta_input'  => [
					DataBase::LIST_KEY     => $unique_id,
					DataBase::STORAGE_KEY  => $storage_key, // wp-users, wp-taxonomy_tag, post, page etc.
					DataBase::SETTINGS_KEY => [
						'roles' => [],
						'users' => [],
					],
					DataBase::COLUMNS_KEY  => $setting->columns,
				],
			];

			$layout_settings = $this->get_layout_settings( $storage_key );

			if ( $layout_settings ) {

				// Add layout settings
				if ( $layout_settings->id ) {
					$post_data['meta_input'][ DataBase::LIST_KEY ] = $layout_settings->id;

					// remove layout ID from list type
					$post_data['meta_input'][ DataBase::STORAGE_KEY ] = $this->remove_suffix( $layout_settings->id, $storage_key );
				}
				if ( $layout_settings->name ) {
					$post_data['post_title'] = $layout_settings->name;
				}
				if ( $layout_settings->users ) {
					$post_data['meta_input'][ DataBase::SETTINGS_KEY ]['users'] = $layout_settings->users;
				}
				if ( $layout_settings->roles ) {
					$post_data['meta_input'][ DataBase::SETTINGS_KEY ]['roles'] = $layout_settings->roles;
				}

				$posts_data[] = $post_data;
			} else {
				$id = $this->contains_layout_id( $storage_key );

				if ( $id ) {
					$post_data['meta_input'][ DataBase::LIST_KEY ] = $id;
					$post_data['meta_input'][ DataBase::STORAGE_KEY ] = $this->remove_suffix( $id, $storage_key );
				}
			}

			$result = wp_insert_post( $post_data, true );

			// Remove old data
			if ( ! is_wp_error( $result ) ) {
				// todo
				//$wpdb->delete( $wpdb->options, array( 'option_id' => $setting->option_id ) );
			}
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
	 * @param string $storage_key
	 *
	 * @return object
	 */
	private function get_layout_settings( $storage_key ) {
		return get_option( 'cpac_layouts' . $storage_key );
	}

}