<?php
/**
 * @since NEWVERSION
 */
class CPAC_Column_Post_Date_Published extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since NEWVERSION
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	= 'column-date_published';
		$this->properties['label']	= __( 'Date Published' );

		// Options
		$this->options['date_format'] = '';
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since NEWVERSION
	 */
	public function get_value( $post_id ) {

		$raw_value = $this->get_raw_value( $post_id );

		if ( ! $this->get_option( 'date_format' ) ) {
			return $this->get_date( $raw_value ) . ' ' . $this->get_time( $raw_value );
		}

		return $this->get_date( $raw_value, $this->get_option( 'date_format' ) );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since NEWVERSION
	 */
	public function get_raw_value( $post_id ) {

		$post = get_post( $post_id );

		return $post->post_date;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since NEWVERSION
	 */
	public function display_settings() {

		parent::display_settings();

		$this->display_field_date_format();
	}

}