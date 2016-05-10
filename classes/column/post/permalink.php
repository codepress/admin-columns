<?php

/**
 * Columng displaying full item permalink (including URL).
 *
 * @since 2.0
 */
class CPAC_Column_Post_Permalink extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-permalink';
		$this->properties['label'] = __( 'Permalink', 'codepress-admin-columns' );

		// Options
		$this->options['link_to_post'] = false;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		$value = $this->get_raw_value( $post_id );
		if ( 'on' == $this->get_option( 'link_to_post' ) ) {
			$value = '<a href="' . esc_attr( $value ) . '" target="_blank">' . $value . '</a>';
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return get_permalink( $post_id );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.1
	 */
	public function display_settings() {
		$this->form_field( 'radio', array(
			'name'        => 'link_to_post',
			'label'       => __( 'Link to post', 'codepress-admin-columns' ),
			'description' => __( 'This will make the permalink clickable.', 'codepress-admin-columns' ),
			'options'     => array(
				'on'  => __( 'Yes' ),
				'off' => __( 'No' ),
			),
			'default'     => 'off'
		) );
	}
}