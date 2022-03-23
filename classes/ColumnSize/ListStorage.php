<?php

namespace AC\ColumnSize;

use AC;
use AC\ListScreen;
use AC\ListScreenRepository;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class ListStorage {

	/**
	 * @var ListScreenRepository
	 */
	private $list_screen_repository;

	public function __construct( ListScreenRepository $list_screen_repository ) {
		$this->list_screen_repository = $list_screen_repository;
	}

	/**
	 * @param ListScreenId $list_id
	 * @param string       $column_name
	 * @param ColumnWidth  $column_width
	 */
	public function save( ListScreenId $list_id, $column_name, ColumnWidth $column_width ) {
		$list_screen = $this->list_screen_repository->find( $list_id );

		if ( ! $list_screen ) {
			return;
		}

		$settings = $list_screen->get_settings();

		foreach ( $settings as $_column_name => $setting ) {
			if ( $_column_name !== $column_name ) {
				continue;
			}

			$settings[ $_column_name ]['width'] = $column_width->get_value();
			$settings[ $_column_name ]['width_unit'] = $column_width->get_unit();
		}

		$list_screen->set_settings( $settings );

		$this->list_screen_repository->save( $list_screen );
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