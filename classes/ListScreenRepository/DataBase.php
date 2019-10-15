<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenFactory;
use AC\PostTypes;
use AC\Storage\DataObject;
use DateTime;
use LogicException;

class DataBase implements Write, ListScreenRepository {

	const TYPE_KEY = 'type';
	const SUBTYPE_KEY = 'subtype';
	const SETTINGS_KEY = 'settings';
	const COLUMNS_KEY = 'columns';
	const LIST_KEY = 'list_id';

	/** @var ListScreenFactory */
	private $list_screen_factory;

	public function __construct( $list_screen_factory ) {
		$this->list_screen_factory = $list_screen_factory;
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

		if ( isset( $args['type'] ) ) {
			$query_args['meta_query'][] = [
				'key'   => self::TYPE_KEY,
				'value' => $args['type'],
			];
		}

		if ( isset( $args['subtype'] ) ) {
			$query_args['meta_query'][] = [
				'key'   => self::SUBTYPE_KEY,
				'value' => $args['subtype'],
			];
		}

		// todo return ListScreenDataCollection
		$list_screens = [];

		foreach ( get_posts( $query_args ) as $id ) {
			$list_screens[] = $this->create_list_screen_by_data( $this->read_post( $id ) );
		}

		return new ListScreenCollection( $list_screens );
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

		return $this->create_list_screen_by_data( $this->read_post( $post_id ) );
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
		}

		$this->update_post( $post_id, $list_screen );
	}

	public function delete( ListScreen $list_screen ) {
		$post_id = $this->get_post_id_by_list_id( $list_screen->get_layout_id() );

		if ( ! $post_id ) {
			return;
		}

		// todo: remove?
		do_action( 'ac/columns_delete', $list_screen );

		$this->delete_post( $post_id );
	}

	private function create_list_screen_by_data( DataObject $data ) {
		if ( ! $data->type ) {
			return null;
		}

		return $this->list_screen_factory->create( $data->type, $data );
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

	private function read_post( $post_id ) {
		$date_modified = DateTime::createFromFormat( 'Y-m-d H:i:s', get_post_field( 'post_modified_gmt', $post_id ) );
		$date_created = DateTime::createFromFormat( 'Y-m-d H:i:s', get_post_field( 'post_date_gmt', $post_id ) );

		return new DataObject( [
			'title'         => get_post_field( 'post_title', $post_id ),
			'date_modified' => $date_modified ? $date_modified->getTimestamp() : false,
			'date_created'  => $date_created ? $date_created->getTimestamp() : false,
			'order'         => get_post_field( 'menu_order', $post_id ),
			'list_id'       => get_post_meta( $post_id, self::LIST_KEY, true ),
			'type'          => get_post_meta( $post_id, self::TYPE_KEY, true ),
			'subtype'       => get_post_meta( $post_id, self::SUBTYPE_KEY, true ),
			'settings'      => get_post_meta( $post_id, self::SETTINGS_KEY, true ),
			'columns'       => get_post_meta( $post_id, self::COLUMNS_KEY, true ),
		] );
	}

	private function update_post( $post_id, ListScreen $list_screen ) {
		wp_update_post( [
			'ID'         => $post_id,
			'post_title' => $list_screen->get_title(),
			'meta_input' => [
				self::TYPE_KEY     => $list_screen->get_key(),
				self::SUBTYPE_KEY  => false,
				self::SETTINGS_KEY => $list_screen->get_preferences(), // todo
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