<?php

class AC_API {

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
	 * @param AC_ListScreen $list_screen
	 */
	public function set_column_settings( AC_ListScreen $list_screen ) {
		if ( $columndata = $this->get_columndata( $list_screen->get_key() ) ) {
			foreach ( $columndata as $data ) {
				if ( $list_screen->get_storage_key() === $list_screen->get_key() . $data['layout']['id'] ) {
					$list_screen->set_settings( $data['columns'] )->set_read_only( true );
				}
			}
		}
	}

	/**
	 * @param AC_ListScreen $list_screen
	 * @return array
	 */
	public function get_layout_settings( $list_screen ) {
		$layouts = array();
		if ( $columndata = $this->get_columndata( $list_screen->get_key() ) ) {
			foreach ( $columndata as $data ) {
				$layouts[] = $data['layout'];
			}
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

}