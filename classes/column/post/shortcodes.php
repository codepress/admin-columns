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

		$display = array();
		foreach ( $shortcodes as $sc => $count ) {
			$string = '[' . $sc . ']';
			$string = $count > 1 ? $string . '<span class="cpac-rounded">' . $count . '</span>' : $string;
			$display[ $sc ] = '<span class="cpac-spacing">' . $string . '</span>';
		}

		return implode( ' ', $display );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.5
	 */
	public function get_raw_value( $post_id ) {
		global $shortcode_tags;

		if ( ! $shortcode_tags ) {
			return false;
		}

		$content = get_post_field( 'post_content', $post_id );

		$shortcodes = array();

		$_shortcodes = array_keys( $shortcode_tags );
		asort( $_shortcodes );

		foreach ( $_shortcodes as $sc ) {
			if ( $count = substr_count( $content, '[' . $sc ) ) {
				$shortcodes[ $sc ] = $count;
			}
		}

		return $shortcodes;
	}
}