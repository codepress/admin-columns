<?php

namespace AC\Storage;

use AC\ListScreenRepositoryWritable;
use AC\Type\ListScreenId;

class ListColumnOrder {

	private $list_screen_repository;

	public function __construct( ListScreenRepositoryWritable $list_screen_repository ) {
		$this->list_screen_repository = $list_screen_repository;
	}

	public function save( ListScreenId $list_id, array $column_names ): void {
		$list_screen = $this->list_screen_repository->find( $list_id );

		if ( ! $list_screen ) {
			return;
		}

		$settings = $list_screen->get_settings();

		if ( ! $settings ) {
			return;
		}

		$ordered = [];

		foreach ( $column_names as $column_name ) {
			if ( ! isset( $settings[ $column_name ] ) ) {
				continue;
			}

			$ordered[ $column_name ] = $settings[ $column_name ];

			unset( $settings[ $column_name ] );
		}

		$settings = array_merge( $ordered, $settings );

		$list_screen->set_settings( $settings );

		$this->list_screen_repository->save( $list_screen );
	}

}