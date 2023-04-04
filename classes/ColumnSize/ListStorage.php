<?php

namespace AC\ColumnSize;

use AC;
use AC\ListScreen;
use AC\ListScreenRepositoryWritable;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class ListStorage {

	/**
	 * @var ListScreenRepositoryWritable
	 */
	private $list_screen_repository;

	public function __construct( ListScreenRepositoryWritable $list_screen_repository ) {
		$this->list_screen_repository = $list_screen_repository;
	}

	public function save( ListScreenId $list_id, string $column_name, ColumnWidth $column_width ): void {
		$list_screen = $this->list_screen_repository->find( $list_id );

		if ( ! $list_screen ) {
			return;
		}

		$settings = $list_screen->get_settings();

		foreach ( $settings as $_column_name => $setting ) {
			if ( $_column_name !== $column_name ) {
				continue;
			}

			$settings[ $_column_name ]['width'] = (string) $column_width->get_value();
			$settings[ $_column_name ]['width_unit'] = $column_width->get_unit();
		}

		$list_screen->set_settings( $settings );

		$this->list_screen_repository->save( $list_screen );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return ColumnWidth[]
	 */
	public function get_all( ListScreen $list_screen ) {
		$results = [];

		foreach ( $list_screen->get_columns() as $column ) {
			$name = $column->get_name();

			$results[ $name ] = $this->get( $list_screen, $name );
		}

		return array_filter( $results );
	}

	/**
	 * @param ListScreen $list_screen
	 * @param string     $column_name
	 *
	 * @return ColumnWidth|null
	 */
	public function get( ListScreen $list_screen, $column_name ) {
		$column = $list_screen->get_column_by_name( $column_name );

		if ( ! $column ) {
			return null;
		}

		$setting = $column->get_setting( 'width' );

		return $setting instanceof AC\Settings\Column\Width
			? $setting->get_column_width()
			: null;
	}

}