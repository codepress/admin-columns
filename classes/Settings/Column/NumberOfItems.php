<?php

class AC_Settings_Column_NumberOfItems extends AC_Settings_Column {

	/**
	 * @var string
	 */
	private $number_of_items;

	protected function set_name() {
		return $this->name = 'number_of_items';
	}

	protected function define_options() {
		return array(
			'number_of_items',
		);
	}

	public function create_view() {
		$item_limit = $this->create_element( 'number' )
		                   ->set_attribute( 'placeholder', 0 )
		                   ->set_attribute( 'step', 1 );

		$view = new AC_View( array(
			'label'   => __( 'Number of Items', 'codepress-admin-columns' ),
			'tooltip' => __( 'Maximum number of items', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
			'setting' => $item_limit,
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_number_of_items() {
		return $this->number_of_items;
	}

	/**
	 * @param string $number_of_items
	 *
	 * @return bool
	 */
	public function set_number_of_items( $number_of_items ) {
		if ( $number_of_items ) {
			$this->number_of_items = absint( $number_of_items );
		}

		return true;
	}

}