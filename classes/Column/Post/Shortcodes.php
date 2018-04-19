<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * Display used shortcodes
 *
 * @since 2.3.5
 */
class Shortcodes extends Column {

	public function __construct() {
		$this->set_type( 'column-shortcode' );
		$this->set_label( __( 'Shortcodes', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		if ( ! ( $shortcodes = $this->get_raw_value( $post_id ) ) ) {
			return false;
		}

		$display = array();
		foreach ( $shortcodes as $sc => $count ) {
			$string = '[' . $sc . ']';

			if ( $count > 1 ) {
				$string .= ac_helper()->html->rounded( $count );
			}

			$display[ $sc ] = '<span class="ac-spacing">' . $string . '</span>';
		}

		return implode( ' ', $display );
	}

	public function get_raw_value( $post_id ) {
		global $shortcode_tags;

		if ( ! $shortcode_tags ) {
			return false;
		}

		$content = get_post_field( 'post_content', $post_id );

		$shortcodes = array();

		$_shortcodes = array_keys( $shortcode_tags );
		asort( $_shortcodes );

		foreach ( $_shortcodes as $shortcode ) {

			$count = substr_count( $content, '[' . $shortcode . ']' );
			$count += substr_count( $content, '[' . $shortcode . ' ' );

			if ( $count ) {
				$shortcodes[ $shortcode ] = $count;
			}
		}

		return $shortcodes;
	}

}