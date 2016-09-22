<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_Html {

	/**
	 * @param string $url
	 * @param string $label
	 *
	 * @return string|false HTML Anchor element
	 */
	public function link( $url, $label, $attributes = array() ) {
		if ( ! $url ) {
			return $label;
		}

		if ( ! $this->contains_html( $label ) ) {
			$label = esc_html( $label );
		}

		return $label || '0' === $label ? '<a href="' . esc_url( $url ) . '"' . $this->get_attributes( $attributes ) . '>' . $label . '</a>' : false;
	}

	/**
	 * @since 2.5
	 */
	public function divider() {
		return '<span class="cpac-divider"></span>';
	}

	/**
	 * @param array $attributes
	 *
	 * @return string
	 */
	private function get_attributes( $attributes ) {
		$_attributes = array();
		foreach ( array( 'title', 'id', 'class', 'style', 'target' ) as $attribute ) {
			if ( ! empty( $attributes[ $attribute ] ) ) {
				$_attributes[] = $attribute . '="' . esc_attr( $attributes[ $attribute ] ) . '"';
			}
		}

		return ' ' . implode( ' ', $_attributes );
	}

	/**
	 * @param string $string
	 *
	 * @return bool
	 */
	private function contains_html( $string ) {
		return $string && is_string( $string ) ? $string !== strip_tags( $string ) : false;
	}

}