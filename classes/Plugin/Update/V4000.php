<?php

namespace AC\Plugin\Update;

use AC\ListScreenRepository\DataBase;
use AC\Plugin\Update;
use AC\Storage\ListScreenOrder;
use DateTime;

class V4000 extends Update {

	const LAYOUT_PREFIX = 'cpac_layouts';
	const COLUMNS_PREFIX = 'cpac_options_';

	public function apply_update() {
		// $replaced_list_ids contains a list of empty id's that are replaced with unique id's
		$replaced_list_ids = $this->migrate_list_screen_settings();

		echo '<pre>';
		print_r( $replaced_list_ids );
		echo '</pre>';
		$this->migrate_list_screen_order();
		$this->migrate_list_screen_user_preferences( $replaced_list_ids );
	}

	protected function set_version() {
		$this->version = '4.0.0beta';
	}

	private function migrate_list_screen_user_preferences( array $list_ids ) {

		// 1. Preference "Segments": ac_preferences_search_segments
		$this->migrate_user_preferences_segments( $list_ids );


		// 2. Preference "Horizontal Scrolling": ac_preferences_show_overflow_table
		$this->migrate_user_preferences_show_horizontal_scroll( $list_ids );

		// 3. Preference "Sort": ac_preferences_sorted_by
		$this->migrate_user_preferences_sort_by( $list_ids );

		// 7. Preference "Table selection": wp_ac_preferences_layout_table
		$this->migrate_user_preferences_table_selection( $list_ids );
	}

	private function migrate_user_preferences_segments( array $list_ids ) {
		global $wpdb;

		foreach ( $list_ids as $list_key => $ids ) {
			foreach ( $ids as $deprecated_id => $list_id ) {

				$old_meta_key = 'ac_preferences_search_segments_' . ( $deprecated_id ? $deprecated_id : $list_key );
				$new_meta_key = $wpdb->get_blog_prefix() . 'ac_preferences_search_segments_' . $list_id;

				$sql = $wpdb->prepare( "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = %s", $old_meta_key );

				$results = $wpdb->get_results( $sql );

				foreach ( $results as $row ) {
					$wpdb->insert(
						$wpdb->usermeta,
						[
							'user_id'    => $row->user_id,
							'meta_key'   => $new_meta_key,
							'meta_value' => $row->meta_value,
						],
						[
							'%d',
							'%s',
							'%s',
						]
					);
				}
			}

		}
	}

	private function migrate_user_preferences_sort_by( array $list_ids ) {
		global $wpdb;

		$meta_key = $wpdb->base_prefix . 'ac_preferences_sorted_by';

		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->usermeta} WHERE meta_key = %s ", $meta_key ), ARRAY_A );
		$results = array_map( function ( $a ) {
			$a['meta_value'] = unserialize( $a['meta_value'] );

			return $a;
		}, $results );

		foreach ( $list_ids as $list_key => $ids ) {

			foreach ( $ids as $deprecated_id => $list_id ) {

				$storage_key = $deprecated_id ? $deprecated_id : $list_key;
				$new_storage_key = $list_key . $list_id;

				foreach ( $results as &$result ) {

					if ( isset( $result['meta_value'][ $storage_key ] ) ) {
						$result['meta_value'][ $new_storage_key ] = $result['meta_value'][ $storage_key ];
						unset( $result['meta_value'][ $storage_key ] );
					}

				}
				unset( $result );
			}
		}

		foreach ( $results as $result ) {
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->usermeta} SET meta_value = %s WHERE umeta_id = %d ", serialize( $result['meta_value'] ), $result['umeta_id'] ) );
		}

	}

	private function migrate_user_preferences_show_horizontal_scroll( array $list_ids ) {
		// todo
	}

	private function migrate_user_preferences_table_selection( array $list_ids ) {
		// todo
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
			if ( $ids && is_array( $ids ) ) {
				$order->set( $list_screen_key, $ids );
			}
		}
	}

	/**
	 * @return object[]
	 */
	private function get_layouts_data() {
		global $wpdb;

		$prefix = self::LAYOUT_PREFIX;

		$sql = "
			SELECT option_name, option_value
			FROM {$wpdb->options}
			WHERE option_name LIKE '{$prefix}%'
			ORDER BY `option_id` DESC
		";

		$results = $wpdb->get_results( $sql );

		$layouts = [];

		foreach ( $results as $row ) {
			$data = maybe_unserialize( $row->option_value );

			if ( is_object( $data ) ) {

				$list_id = $data->id;

				$list_key = $this->remove_prefix( self::LAYOUT_PREFIX, $row->option_name );
				$list_key = $this->remove_suffix( $list_id, $list_key );

				if ( ! $list_key ) {
					continue;
				}

				$layout_data = [
					'id'    => $list_id,
					'key'   => $list_key,
					'name'  => '',
					'users' => [],
					'roles' => [],
				];

				if ( ! empty( $data->name ) ) {
					$layout_data['name'] = $data->name;
				}
				if ( ! empty( $data->users ) && is_array( $data->users ) ) {
					$layout_data['users'] = array_map( 'intval', $data->users );
				}
				if ( ! empty( $data->roles ) && is_array( $data->roles ) ) {
					$layout_data['roles'] = array_map( 'strval', $data->roles );
				}

				$layouts[] = $layout_data;
			}
		}

		return $layouts;
	}

	private function get_columns_data() {
		global $wpdb;

		$prefix = self::COLUMNS_PREFIX;

		$sql = "
			SELECT option_name, option_value
			FROM {$wpdb->options}
			WHERE option_name LIKE '{$prefix}%'
			ORDER BY `option_id` DESC
		";

		$results = $wpdb->get_results( $sql );

		$columns = [];

		foreach ( $results as $row ) {
			if ( $this->has_suffix( '__default', $row->option_name ) ) {
				continue;
			}

			$storage_key = $this->remove_prefix( self::COLUMNS_PREFIX, $row->option_name );

			$column_data = maybe_unserialize( $row->option_value );

			$columns[ $storage_key ] = $column_data && is_array( $column_data ) ? $column_data : [];
		}

		return $columns;
	}

	/**
	 * @return array List of replaced id's
	 */
	private function migrate_list_screen_settings() {
		$migrate = [];

		/** @var array $replaced_list_ids array( $list_key => array( $deprecated_list_id => $new_list_id ) ) */
		$replaced_list_ids = [];

		// 1. clear DB table
		$this->clear_table();

		// 2. Fetch data
		$layouts_data = $this->get_layouts_data();
		$columns_data = $this->get_columns_data();

		// 3. Process Pro settings
		foreach ( $layouts_data as $layout_data ) {

			$list_key = $layout_data['key'];

			$storage_key = $list_key . $layout_data['id'];

			$columns = [];

			// Check for column settings
			if ( isset( $columns_data[ $storage_key ] ) ) {
				$columns = $columns_data[ $storage_key ];

				// Remove used column settings from stack
				unset( $columns_data[ $storage_key ] );
			}

			$settings = [];
			if ( $layout_data['users'] ) {
				$settings['users'] = $layout_data['users'];
			}
			if ( $layout_data['roles'] ) {
				$settings['roles'] = $layout_data['roles'];
			}

			$list_id = $layout_data['id'];

			if ( ! $layout_data['id'] ) {
				$list_id = uniqid();

				// add to list of id's that have been replaced
				$replaced_list_ids[ $list_key ][''] = $list_id;
			}

			$list_data = [
				'id'       => $list_id,
				'key'      => $list_key,
				'title'    => $layout_data['name'],
				'columns'  => $columns,
				'settings' => $settings,
			];

			$migrate[] = $list_data;
		}

		// 4. Process Free column settings
		foreach ( $columns_data as $list_key => $columns ) {
			if ( empty( $columns ) ) {
				continue;
			}

			// Skip columns that contain a list ID
			if ( $this->contains_list_id( $list_key ) ) {
				continue;
			}

			$list_id = uniqid();

			$list_data = [
				'id'       => $list_id,
				'key'      => $list_key,
				'title'    => __( 'Original', 'codepress-admin-columns' ),
				'settings' => [],
				'columns'  => $columns,
			];

			$migrate[] = $list_data;

			// add to list of id's that have been replaced
			$replaced_list_ids[ $list_key ][''] = $list_id;
		}

		// 5. Make sure all ID's are unique.
		// A duplicate `id` is possible when a user manually exported their list screen settings and
		// changed the list screen `key` (e.g. from post to page), without changing the `id`, and imported these settings.
		$migrate = $this->unique_ids( $migrate );

		// 6. Insert into DB
		foreach ( $migrate as $list_data ) {
			$this->insert( $list_data );
		}

		return $replaced_list_ids;
	}

	/**
	 * @param array $migrate
	 *
	 * @return array
	 */
	private function unique_ids( array $migrate ) {
		$ids = [];

		foreach ( $migrate as $k => $list_data ) {

			if ( in_array( $list_data['id'], $ids, true ) ) {
				// Truly eliminates a duplicate $unique_id, because `uniqid()` is based on the current time in microseconds
				usleep( 1000 );

				$migrate[ $k ]['id'] = uniqid();
			}

			$ids[] = $list_data['id'];
		}

		return $migrate;
	}

	private function clear_table() {
		global $wpdb;

		$wpdb->query( "TRUNCATE TABLE " . $wpdb->prefix . DataBase::TABLE );
	}

	private function insert( array $data ) {
		global $wpdb;

		$date = new DateTime();

		$wpdb->insert(
			$wpdb->prefix . DataBase::TABLE,
			[
				'title'         => $data['title'],
				'list_id'       => $data['id'],
				'list_key'      => $data['key'],
				'columns'       => serialize( $data['columns'] ),
				'settings'      => serialize( $data['settings'] ),
				'date_modified' => $date->format( 'Y-m-d H:i:s' ),
				'date_created'  => $date->format( 'Y-m-d H:i:s' ),
			],
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			]
		);
	}

	/**
	 * @param string $storage_key
	 *
	 * @return string|false ID
	 */
	private function contains_list_id( $storage_key ) {
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
	 * @param string $suffix
	 * @param string $string
	 *
	 * @return bool
	 */
	private function has_suffix( $suffix, $string ) {
		return substr( $string, strlen( $suffix ) * -1 ) === $suffix;
	}

}