<?php
/**
 * CPAC_Column_Post_Word_Count
 *
 * @since 2.0
 */
class CPAC_Column_Post_Word_Count extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-word_count';
		$this->properties['label']	 	= __( 'Word count', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return $this->str_count_words( $this->strip_trim( get_post_field( 'post_content', $post_id ) ) );
	}

	/**
	 * Count the number of words in a string (multibyte-compatible)
	 *
	 * @since 2.3
	 *
	 * @param string $input Input string
	 * @return int Number of words
	 */
	public function str_count_words( $input ) {

		$patterns = array(
			'strip' => '/<[a-zA-Z\/][^<>]*>/',
			'clean' => '/[0-9.(),;:!?%#$¿\'"_+=\\/-]+/',
			'w' => '/\S\s+/',
			'c' => '/\S/'
		);

		$type = 'w';

		$input = preg_replace( $patterns['strip'], ' ', $input );
		$input = preg_replace( '/&nbsp;|&#160;/i', ' ', $input );
		$input = preg_replace( $patterns['clean'], '', $input );

		if ( ! strlen( preg_replace( '/\s/', '', $input ) ) ) {
			return 0;
		}

		return preg_match_all( $patterns[ $type ], $input ) + 1;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	function apply_conditional() {

		if ( post_type_supports( $this->storage_model->key, 'editor' ) ) {
			return true;
		}

		return false;
	}
}