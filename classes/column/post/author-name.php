<?php
/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class CPAC_Column_Post_Author_Name extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 			= 'column-author_name';
		$this->properties['label']	 			= __( 'Display Author As', 'cpac' );
		$this->properties['is_cloneable']		= true;
		$this->properties['object_property']	= 'post_author';

		// Options
		$this->options['display_author_as'] = '';
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {

		$value = '';

		if ( $user_id = $this->get_raw_value( $post_id ) ) {
			$value = $this->get_display_name( $user_id );
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {

		return get_post_field( 'post_author', $post_id );
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {

		$this->display_field_user_format();
	}
}