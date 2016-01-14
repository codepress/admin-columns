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
		$this->properties['label']	 = __( 'Content', 'codepress-admin-columns' );

		// Options
		$this->options['excerpt_length'] = 15;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {
		return $this->get_shortened_string( $this->get_raw_value( $id ), $this->options->excerpt_length );
	}

	/**
	 * @since 2.4.2
	 */
	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		return $comment->comment_content;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {
		$this->display_field_excerpt_length();
	}
}