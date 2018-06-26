<?php

namespace AC;

class API {

	/**
	 * @var array [
	 *      $listscreen_key => [
	 *          [ 'columns' ][ array $column_settings ]
	 *          [ 'layout' ][ array $layout_settings ]
	 *      ]
	 * ]
	 */
	private $columndata;

	/**
	 * @param ListScreen $list_screen
	 */
	public function set_column_settings( ListScreen $list_screen ) {
		$settings = $this->get_column_settings( $list_screen );

		if ( ! $settings ) {
			return;
		}

		$list_screen->set_settings( $settings )->set_read_only( true );
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return array|false
	 */
	public function get_column_settings( ListScreen $list_screen ) {
		$columndata = $this->get_columndata( $list_screen->get_key() );

		if ( ! $columndata ) {
			return false;
		}

		foreach ( $columndata as $data ) {
			if ( $list_screen->get_storage_key() === $list_screen->get_key() . $data['layout']['id'] ) {
				return $data['columns'];
			}
		}

		return false;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return array
	 */
	public function get_layouts_settings( $list_screen ) {
		$columndata = $this->get_columndata( $list_screen->get_key() );

		if ( ! $columndata ) {
			return array();
		}

		$layouts = array();
		foreach ( $columndata as $data ) {
			$layouts[] = $data['layout'];
		}

		return $layouts;
	}

	/**
	 * @param string|array $list_screen_key List screen key or keys
	 * @param array        $column_data
	 */
	public function load_columndata( $list_screen_keys, $columndata ) {
		foreach ( (array) $list_screen_keys as $list_screen_key ) {
			$this->add_columndata( $list_screen_key, $columndata );
		}
	}

	/**
	 * @param string $list_screen_key List screen key
	 * @param array  $column_data
	 */
	private function add_columndata( $list_screen_key, $columndata ) {
		$columndata = $this->convert_old_format_to_current( $columndata );
		$columndata = $this->set_as_read_only( $columndata );

		$this->columndata[ $list_screen_key ] = array_merge( $this->get_columndata( $list_screen_key ), $columndata );
	}

	/**
	 * @param string $list_screen_key
	 *
	 * @return array
	 */
	private function get_columndata( $list_screen_key ) {
		if ( ! isset( $this->columndata[ $list_screen_key ] ) ) {
			return array();
		}

		return $this->columndata[ $list_screen_key ];
	}

	/**
	 * @param array $columndata
	 *
	 * @return array
	 */
	private function set_as_read_only( $columndata ) {
		foreach ( $columndata as $k => $column ) {
			$columndata[ $k ]['layout']['read_only'] = true;
		}

		return $columndata;
	}

	/**
	 * Convert the old v3 format to the latest which includes layouts
	 *
	 * @param array $columndata
	 *
	 * @return array
	 */
	private function convert_old_format_to_current( $columndata ) {

		// Convert old export formats to new layout format
		$old_format_columns = array();
		foreach ( $columndata as $k => $data ) {
			if ( ! isset( $data['columns'] ) ) {
				$old_format_columns[ $k ] = $data;
				unset( $columndata[ $k ] );
			}
		}

		if ( $old_format_columns ) {
			array_unshift( $columndata, array( 'columns' => $old_format_columns ) );
		}

		// Add layout if missing
		foreach ( $columndata as $k => $data ) {
			if ( ! isset( $data['layout'] ) ) {

				$columndata[ $k ] = array(
					'columns' => isset( $data['columns'] ) ? $data['columns'] : $data,
					'layout'  => array(
						// unique id based on settings
						'id'   => sanitize_key( substr( md5( serialize( $data ) ), 0, 16 ) ),
						'name' => __( 'Imported' ) . ( $k ? ' #' . $k : '' ),
					),
				);
			}
		}

		return $columndata;
	}

	/**
	 * @param string $json JSON encoded settings
	 */
	public function load_from_json( $json ) {
		$array = json_decode( $json, true );

		$this->load_from_array( $array );
	}

	/**
	 * @param array $array
	 */
	public function load_from_array( $array ) {
		foreach ( $array as $list_screen => $columndata ) {
			$this->load_columndata( $list_screen, $columndata );
		}
	}

}