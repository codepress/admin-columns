<?php

class AC_Helper_Html {

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_attribute_as_string( $key, $value ) {
		return sprintf( '%s="%s"', $key, esc_attr( $value ) );
	}

	/**
	 * @param array $attributes
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_attributes_as_string( array $attributes ) {
		$output = array();

		foreach ( $attributes as $key => $value ) {
			$output[] = $this->get_attribute_as_string( $key, $value );
		}

		return implode( ' ', $output );
	}

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
				$_attributes[] = $this->get_attribute_as_string( $attribute, $attributes[ $attribute ] );
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

	/**
	 * Display indicator icon in the column settings header
	 *
	 * @param string $name
	 */
	public function indicator( $class, $id, $title = false ) { ?>
		<span class="indicator-<?php echo esc_attr( $class ); ?>" data-indicator-id="<?php echo esc_attr( $id ); ?>" title="<?php echo esc_attr( $title ); ?>"></span>
		<?php
	}

}