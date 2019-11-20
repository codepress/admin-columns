<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenTypes;
use AC\PostTypes;
use DateTime;
use LogicException;

class DataBase implements Write, ListScreenRepository {

	const STORAGE_KEY = 'key';
	const TYPE_KEY = 'type';
	const SUBTYPE_KEY = 'subtype';
	const SETTINGS_KEY = 'settings';
	const COLUMNS_KEY = 'columns';
	const LIST_KEY = 'list_id';

	/** @var ListScreenTypes */
	private $list_screen_types;

	public function __construct( ListScreenTypes $list_screen_types ) {
		$this->list_screen_types = $list_screen_types;
	}

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] ) {
		$query_args = [
			'post_type'      => PostTypes::LIST_SCREEN_DATA,
			'posts_per_page' => -1,
			'fields'         => 'ids',
		];

		if ( isset( $args['key'] ) ) {
			$query_args['meta_query'][] = [
				'key'   => self::STORAGE_KEY,
				'value' => $args['key'],
			];
		}

		$list_screens = new ListScreenCollection();

		foreach ( get_posts( $query_args ) as $post_id ) {
			$list_screen = $this->create_list_screen( (int) $post_id );

			if ( null === $list_screen ) {
				continue;
			}

			$list_screens->push( $list_screen );
		}

		return $list_screens;
	}

	/**
	 * @param string $list_id
	 *
	 * @return ListScreen|null
	 */
	public function find( $list_id ) {
		$post_id = $this->get_post_id_by_list_id( $list_id );

		if ( ! $post_id ) {
			return null;
		}

		return $this->create_list_screen( (int) $post_id );
	}

	/**
	 * @param ListScreen $data
	 *
	 * @return void
	 */
	public function save( ListScreen $list_screen ) {
		$post_id = $this->get_post_id_by_list_id( $list_screen->get_layout_id() );

		if ( ! $post_id ) {
			$this->create_post( $list_screen );
			return;
		}

		$this->update_post( $post_id, $list_screen );
	}

	public function delete( ListScreen $list_screen ) {
		$post_id = $this->get_post_id_by_list_id( $list_screen->get_layout_id() );

		if ( ! $post_id ) {
			return;
		}

		/**
		 * Fires before a column setup is removed from the database
		 * Primarily used when columns are deleted through the Admin Columns settings screen
		 *
		 * @param ListScreen $list_screen
		 *
		 * @deprecated NEWVERSION
		 * @since      3.0.8
		 */
		do_action( 'ac/columns_delete', $list_screen );

		$this->delete_post( $post_id );
	}

	private function create_list_screen( $post_id ) {
		$key = get_post_meta( $post_id, self::STORAGE_KEY, true );

		// create a cloned reference
		$list_screen = $this->list_screen_types->get_list_screen_by_key( $key );

		if ( null === $list_screen ) {
			// todo exception
			return null;
		}

		$list_screen->set_title( get_post_field( 'post_title', $post_id ) )
		            ->set_layout_id( get_post_meta( $post_id, self::LIST_KEY, true ) )
		            ->set_updated( DateTime::createFromFormat( 'Y-m-d H:i:s', get_post_field( 'post_modified_gmt', $post_id ) ) );

		$preferences = get_post_meta( $post_id, self::SETTINGS_KEY, true );

		if ( $preferences ) {
			$list_screen->set_preferences( $preferences );
		}

		$columns = get_post_meta( $post_id, self::COLUMNS_KEY, true );

		if ( $columns ) {
			$list_screen->set_settings( $columns );
		}

		return $list_screen;
	}

	/**
	 * @param ListScreen $data
	 *
	 * @return int
	 */
	private function create_post( ListScreen $list_screen ) {
		$id = wp_insert_post( [
			'post_status' => 'publish',
			'post_type'   => PostTypes::LIST_SCREEN_DATA,
		] );

		if ( is_wp_error( $id ) ) {
			throw new LogicException( $id->get_error_message() );
		}

		$this->update_post( $id, $list_screen );

		return $id;
	}

	/**
	 * @param string $list_id
	 *
	 * @return int|null
	 */
	private function get_post_id_by_list_id( $list_id ) {
		$ids = get_posts( [
			'post_type'      => PostTypes::LIST_SCREEN_DATA,
			'fields'         => 'ids',
			'posts_per_page' => 1,
			'meta_query'     => [
				[
					'key'   => self::LIST_KEY,
					'value' => $list_id,
				],
			],
		] );

		if ( ! $ids ) {
			return null;
		}

		return $ids[0];
	}

	private function update_post( $post_id, ListScreen $list_screen ) {
		wp_update_post( [
			'ID'         => $post_id,
			'post_title' => $list_screen->get_title(),
			'meta_input' => [
				self::STORAGE_KEY  => $list_screen->get_key(),
				self::TYPE_KEY     => $list_screen->get_type(),
				self::SUBTYPE_KEY  => $list_screen->get_subtype(),
				self::SETTINGS_KEY => $list_screen->get_preferences(),
				self::COLUMNS_KEY  => $list_screen->get_settings(),
				self::LIST_KEY     => $list_screen->get_layout_id(),
			],
		] );
	}

	private function delete_post( $post_id ) {
		wp_delete_post( $post_id, true );
	}

	public function exists( $id ) {
		return null !== $this->get_post_id_by_list_id( $id );
	}

}