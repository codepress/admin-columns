<?php
/**
 * @since 2.4
 */
class CPAC_Column_Post_Date_Published extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.4
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
	 * @since 2.4
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
	 * @since 2.4
	 */
	public function get_raw_value( $post_id ) {

		$post = get_post( $post_id );

		return $post->post_date;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.4
	 */
	public function display_settings() {

		parent::display_settings();

		$this->display_field_date_format();
	}

}