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
		return sprintf( '%s="%s"', $key, esc_attr( trim( $value ) ) );
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
	public function link( $url, $label = null, $attributes = array() ) {
		if ( false === $label ) {
			return $label;
		}

		if ( ! $url ) {
			return $label;
		}

		if ( null === $label ) {
			$label = $url;
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
	 * @param $label
	 * @param $tooltip
	 *
	 * @return string
	 */
	public function tooltip( $label, $tooltip ) {
	    if ( $label ) {
		    $label = '<span data-tip="' . esc_attr( $tooltip ) . '">' . $label . '</div>';
	    }

	    return $label;
	}

	/**
	 * @param string $string
	 * @param int $max_chars
	 *
	 * @return string
	 */
	public function codearea( $string, $max_chars = 1000 ) {
	    if ( ! $string ) {
	        return false;
        }

        return '<textarea style="color: #808080; width: 100%; min-height: 60px;" readonly>' . substr( $string, 0, $max_chars ) . '</textarea>';
	}

	/**
	 * @param array $attributes
	 *
	 * @return string
	 */
	private function get_attributes( $attributes ) {
		$_attributes = array();

		foreach ( $attributes as $attribute => $value ) {
		    if ( in_array( $attribute, array( 'title', 'id', 'class', 'style', 'target' ) ) || 'data-' === substr( $attribute, 0, 5 ) ) {
			    $_attributes[] = $this->get_attribute_as_string( $attribute, $value );
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

	/**
	 * Adds a divider to the implode
	 *
	 * @param $array
	 *
	 * @return string
	 */
	public function implode( $array ) {
		return is_array( $array ) ? implode( $this->divider(), $array ) : $array;
	}

	/**
	 * Remove attribute from an html tag
	 *
	 * @param string $html HTML tag
	 * @param string|array $attribute Attribute: style, class, alt, data etc.
	 *
	 * @return mixed
	 */
	public function strip_attributes( $html, $attributes ) {
		if ( $this->contains_html( $html ) ) {
			foreach ( (array) $attributes as $attribute ) {
				$html = preg_replace( '/(<[^>]+) ' . $attribute . '=".*?"/i', '$1', $html );
			}
		}

		return $html;
	}

	/**
     * Small HTML block with grey background and rounded corners
     *
	 * @param string|array $items
	 *
	 * @return string
	 */
	public function small_block( $items ) {
	    $blocks = array();

        foreach ( (array) $items as $item ) {
            if ( $item && is_string( $item ) ) {
	            $blocks[] = '<span class="ac-small-block">' . $item . '</span>';
            }
        }

        return implode( $blocks );
	}

}