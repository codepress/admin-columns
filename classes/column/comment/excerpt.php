<?php
/**
 * CPAC_Column_Comment_Excerpt
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Excerpt extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 = 'column-excerpt';
		$this->properties['label']	 = __( 'Excerpt', 'cpac' );

		// define additional options
		$this->options['excerpt_length'] = 15;

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return $this->get_shortened_string( $comment->comment_content, $this->options->excerpt_length );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		$this->display_field_excerpt_length();
	}
}