<?php
/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class CPAC_Column_Post_Last_Modified_Author extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 			= 'column-last_modified_author';
		$this->properties['label']	 			= __( 'Last Modified Author', 'cpac' );
		$this->properties['is_cloneable']		= true;

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
		return get_post_meta( $post_id, '_edit_last', true );
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