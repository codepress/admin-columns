<?php
namespace AC\Storage;

use AC\Data;
use AC\PostTypes;
use LogicException;

class ListScreen implements Data {

	const TYPE_KEY = 'type';
	const SUBTYPE_KEY = 'subtype';
	const SETTINGS_KEY = 'settings';
	const COLUMNS_KEY = 'columns';
	const LIST_KEY = 'list_id';

	/**
	 * @param array $args
	 *
	 * @return DataObject[]
	 */
	public function query( array $args ) {
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
		$data_objects = [];

		foreach ( get_posts( $query_args ) as $id ) {
			$data_objects[] = $this->read_post( $id );
		}

		return $data_objects;
	}

	/**
	 * @param string $list_id
	 *
	 * @return DataObject|null
	 */
	public function find_by_id( $list_id ) {
		$post_id = $this->get_post_id_by_list_id( $list_id );

		if ( ! $post_id ) {
			return null;
		}

		return $this->read_post( $post_id );
	}

	/**
	 * @param string     $list_id
	 * @param DataObject $data
	 *
	 * @return null
	 */
	public function update( $list_id, DataObject $data ) {
		$post_id = $this->get_post_id_by_list_id( $list_id );

		if ( ! $post_id ) {
			return;
		}

		$this->update_post( $post_id, $data );
	}

	/**
	 * @param DataObject $data
	 *
	 * @return int
	 */
	public function create( DataObject $data ) {
		$id = wp_insert_post( [
			'post_status' => 'publish',
			'post_type'   => PostTypes::LIST_SCREEN_DATA,
		] );

		if ( is_wp_error( $id ) ) {
			throw new LogicException( $id->get_error_message() );
		}

		$this->update_post( $id, $data );

		return $id;
	}

	public function delete( $id ) {
		$post_id = $this->get_post_id_by_list_id( $id );

		if ( ! $post_id ) {
			return;
		}

		$this->delete_post( $post_id );
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

	private function read_post( $id ) {
		return new DataObject( [
			'post_id'       => $id,
			'title'         => get_post_field( 'post_title', $id ),
			'date_modified' => get_post_field( 'post_modified_gmt', $id ),
			'date_created'  => get_post_field( 'post_date_gmt', $id ),
			'order'         => get_post_field( 'menu_order', $id ),
			'list_id'       => get_post_meta( $id, self::LIST_KEY, true ),
			'type'          => get_post_meta( $id, self::TYPE_KEY, true ),
			'subtype'       => get_post_meta( $id, self::SUBTYPE_KEY, true ),
			'settings'      => get_post_meta( $id, self::SETTINGS_KEY, true ),
			'columns'       => get_post_meta( $id, self::COLUMNS_KEY, true ),
		] );
	}

	private function update_post( $id, DataObject $data ) {
		wp_update_post( [
			'ID'         => $id,
			'post_title' => $data->title,
			'menu_order' => $data->order,
			'meta_input' => [
				self::TYPE_KEY     => $data->type,
				self::SUBTYPE_KEY  => $data->subtype,
				self::SETTINGS_KEY => $data->settings,
				self::COLUMNS_KEY  => $data->columns,
				self::LIST_KEY     => $data->list_id,
			],
		] );
	}

	public function delete_post( $id ) {
		wp_delete_post( $id, true );
	}

}