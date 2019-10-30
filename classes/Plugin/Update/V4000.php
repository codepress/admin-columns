<?php

namespace AC\Plugin\Update;

use AC\ListScreenFactory;
use AC\ListScreenRepository\DataBase;
use AC\Plugin\Update;
use AC\PostTypes;

// todo
class V4000 extends Update {

	/** @var ListScreenFactory */
	private $list_screen_factory;

	/** @var DataBase */
	private $data_base_repository;

	public function __construct( $stored_version ) {
		$this->list_screen_factory = new ListScreenFactory();
		$this->data_base_repository = new DataBase( $this->list_screen_factory );

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
			$name = str_replace( 'cpac_options_', '', $row->option_name );

			// Ignore settings that contain the default columns
			if ( "__default" === substr( $name, -9 ) ) {
				continue;
			}

			$settings[] = (object) [
				'id'    => $row->option_id,
				'name'  => $name,
				'value' => $row->option_value,
			];
		}

		$posts_data = [];

		// 2. convert to post data
		foreach ( $settings as $setting ) {
			$storage_key = $setting->name;

			$column_data = maybe_unserialize( $setting->value );

			if ( ! $column_data ) {
				continue;
			}

			// Defaults
			$post_data = [
				'post_status'   => 'publish',
				'post_type'     => PostTypes::LIST_SCREEN_DATA,
				'post_date'     => time(),
				'post_modified' => time(),
				'post_title'    => __( 'Original', 'codepress-admin-columns' ),
				'meta_input'    => [
					DataBase::LIST_KEY     => uniqid( 'ac' ), // todo: could be based on seconds
					DataBase::TYPE_KEY     => $storage_key, // wp-users, wp-taxonomy_tag, post, page etc.
					DataBase::SUBTYPE_KEY  => '',
					DataBase::SETTINGS_KEY => [
						'roles' => [],
						'users' => [],
					],
					DataBase::COLUMNS_KEY  => $column_data,
				],
			];

			$layout_settings = $this->get_layout_settings( $storage_key );

			// Add layout settings
			if ( $layout_settings ) {

				if ( $layout_settings->id ) {
					$post_data['meta_input'][ DataBase::LIST_KEY ] = $layout_settings->id;

					// remove layout ID from list type
					$post_data['meta_input'][ DataBase::TYPE_KEY ] = $this->remove_string_from_end( $storage_key, $layout_settings->id );
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
				if ( property_exists( $layout_settings, 'updated' ) ) {
					$post_data['post_date'] = $layout_settings->updated;
					$post_data['post_modified'] = $layout_settings->updated;
				}
			}

			$posts_data[] = $post_data;
		}

		// 3. check the invalid
		// todo

		// 4. migrate
		foreach ( $posts_data as $post_data ) {
			wp_insert_post( $post_data );
		}
	}

	/**
	 * @param string $string
	 * @param string $end
	 *
	 * @return string
	 */
	private function remove_string_from_end( $string, $remove ) {
		return (string) preg_replace( '/' . preg_quote( $remove, '/' ) . '$/', '', $string );
	}

	/**
	 * @param string $storage_key
	 *
	 * @return object
	 */
	private function get_layout_settings( $storage_key ) {
		return get_option( 'cpac_layouts' . $storage_key );
	}

	private function mark_as_migrated( $id ) {
		// todo: mark as migrated
	}

}