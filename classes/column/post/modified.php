<?php
/**
 * CPAC_Column_Post_Modified
 *
 * @since 2.0
 */
class CPAC_Column_Post_Modified extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-modified';
		$this->properties['label']	 	= __( 'Last modified', 'cpac' );
		
		// Options
		$this->options['date_format'] = '';
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {

		$modified = $this->get_raw_value( $post_id );

		if ( ! $this->options->date_format ) {
			return $this->get_date( $modified ) . ' ' . $this->get_time( $modified );
		}

		return date( $this->options->date_format, strtotime( $modified ) );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {

		$p = get_post( $post_id );

		return $p->post_modified;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.7
	 */
	function display_settings() {

		$this->display_field_date_format();
	}

}