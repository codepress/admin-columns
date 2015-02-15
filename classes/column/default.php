<?php
/**
 * Column class for default columns (i.e. columns not added by Admin Columns).
 * Allows additional properties, such as editability and filterability, to be
 * added to defeault column types.
 *
 * @since 2.2.1
 */
class CPAC_Column_Default extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-default';
		$this->properties['handle'] = NULL;
	}

	/**
	 * @since 2.2.1
	 * @see CPAC_Column::get_value()
	 */
	public function get_value( $post_id ) {

		if ( ! empty( $this->properties->handle ) ) {
			return $this->storage_model->get_original_column_value( $this->properties->handle, $post_id );
		}

		return '';
	}

}