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
			$data_objects[] = $this->read( $id );
		}

		return $data_objects;
	}

	public function create( DataObject $data ) {
		$id = wp_insert_post( [
			'post_status' => 'publish',
			'post_type'   => PostTypes::LIST_SCREEN_DATA,
		] );

		if ( is_wp_error( $id ) ) {
			throw new LogicException( $id->get_error_message() );
		}

		$this->update( $id, $data );

		return $id;
	}

	public function read( $id ) {
		return new DataObject( [
			'id'            => $id,
			'title'         => get_post_field( 'post_title', $id ),
			'date_modified' => get_post_field( 'post_modified_gmt', $id ),
			'date_created'  => get_post_field( 'post_date_gmt', $id ),
			'order'         => get_post_field( 'menu_order', $id ),
			'type'          => get_post_meta( $id, self::TYPE_KEY, true ),
			'subtype'       => get_post_meta( $id, self::SUBTYPE_KEY, true ),
			'settings'      => get_post_meta( $id, self::SETTINGS_KEY, true ),
			'columns'       => get_post_meta( $id, self::COLUMNS_KEY, true ),
		] );
	}

	public function update( $id, DataObject $data ) {
		wp_update_post( [
			'ID'         => $id,
			'post_title' => $data->title,
			'menu_order' => $data->order,
			'meta_input' => [
				self::TYPE_KEY     => $data->type,
				self::SUBTYPE_KEY  => $data->subtype,
				self::SETTINGS_KEY => $data->settings,
				self::COLUMNS_KEY  => $data->columns,
			],
		] );
	}

	public function delete( $id ) {
		wp_delete_post( $id, true );
	}

}