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
	public function add_columndata( $list_screen_key, $columndata ) {
		$columndata = $this->convert_old_format_to_current( $columndata );

		$this->columndata[ $list_screen_key ] = array_merge( $this->get_columndata( $list_screen_key ), $columndata );
	}

	/**
	 * @param string $list_screen_key
	 *
	 * @return array
	 */
	public function get_columndata( $list_screen_key ) {
		if ( ! isset( $this->columndata[ $list_screen_key ] ) ) {
			return array();
		}

		return $this->columndata[ $list_screen_key ];
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