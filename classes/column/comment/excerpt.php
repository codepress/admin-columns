<?php
/**
 * CPAC_Column_Comment_Excerpt
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Excerpt extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-excerpt';
		$this->properties['label']	 = __( 'Excerpt', 'cpac' );

		// Options
		$this->options['excerpt_length'] = 15;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return $this->get_shortened_string( $comment->comment_content, $this->options->excerpt_length );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}