<?php
/**
 * @since 2.3
 */
class CPAC_Column_Default extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.3
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-default';
		$this->properties['handle'] = NULL;
	}

	/**
	 * @since 2.3
	 * @see CPAC_Column::get_value()
	 */
	public function get_value( $post_id ) {

		if ( $this->properties->column ) {
			echo $this->storage_model->get_original_column_value( $this->properties->handle, $post_id );
		}

		return '';
	}

}