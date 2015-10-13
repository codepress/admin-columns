<?php
/**
 * Display used shortcodes
 *
 * @since 2.3.5
 */
class CPAC_Column_Post_Shortcodes extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.3.4
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-shortcode';
		$this->properties['label'] = __( 'Shortcodes', 'codepress-admin-columns' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.3.5
	 */
	public function get_value( $post_id ) {
		if ( ! ( $shortcodes = $this->get_raw_value( $post_id ) ) ) {
			return false;
		}
		return '[' . implode( '] [', $shortcodes ) . ']';
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.5
	 */
	public function get_raw_value( $post_id ) {

		$content = get_post_field( 'post_content', $post_id );
		$pattern = get_shortcode_regex();

		preg_match_all( "/$pattern/s", $content, $matches );

		if ( ! isset( $matches[2] ) ) {
			return false;
		}

		return $matches[2];
	}
}