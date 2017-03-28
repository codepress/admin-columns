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

		if ( ! $label ) {
			return false;
		}

		if ( ! $this->contains_html( $label ) ) {
			$label = esc_html( $label );
		}

		$allowed = wp_allowed_protocols();
		$allowed[] = 'skype';
		$allowed[] = 'call';

		return '<a href="' . esc_url( $url, $allowed ) . '"' . $this->get_attributes( $attributes ) . '>' . $label . '</a>';
	}

	/**
	 * @since 2.5
	 */
	public function divider() {
		return '<span class="ac-divider"></span>';
	}

	/**
	 * @param $label
	 * @param $tooltip
	 *
	 * @return string
	 */
	public function tooltip( $label, $tooltip ) {
		if ( $label && $tooltip ) {
			$label = '<span data-tip="' . esc_attr( $tooltip ) . '">' . $label . '</span>';
		}

		return $label;
	}

	/**
	 * @param string $string
	 * @param int    $max_chars
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
	public function implode( $array, $divider = true ) {
		if ( ! is_array( $array ) ) {
			return $array;
		}

		// Remove empty values
		$array = $this->remove_empty( $array );

		if ( true === $divider ) {
			$divider = $this->divider();
		}

		return implode( $divider, $array );
	}

	public function remove_empty( $array ) {
		return array_filter( $array, array( ac_helper()->string, 'is_not_empty' ) );
	}

	/**
	 * Remove attribute from an html tag
	 *
	 * @param string       $html      HTML tag
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

	/**
	 * @param array $args
	 *
	 * @return string
	 */
	public function progress_bar( $args = array() ) {
		$defaults = array(
			'current'     => 0,
			'total'       => 100, // -1 is infinitive
			'label_left'  => '',
			'label_right' => '',
			'label_main'  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( -1 === $args['total'] ) {
			$args['current'] = 0;
			$args['total'] = 100;
			$args['label_right'] = '&infin;';
		}

		$args['current'] = absint( $args['current'] );
		$args['total'] = absint( $args['total'] );

		if ( $args['total'] < 0 ) {
			return false;
		}

		$percentage = 0;

		if ( $args['total'] > 0 ) {
			$percentage = round( ( $args['current'] / $args['total'] ) * 100 );
		}

		// Allowed size is zero, but current has a value
		if ( 0 === $args['total'] && $args['current'] > 0 ) {
			$percentage = 101;
		}

		$class = '';
		if ( $percentage > 100 ) {
			$percentage = 100;
			$class = ' full';
		}

		ob_start();
		?>
        <div class="ac-progress-bar<?php echo esc_attr( $class ); ?>">
			<?php if ( $args['label_main'] ) : ?>
                <span class="ac-label-main"><?php echo esc_html( $args['label_main'] ); ?></span>
			<?php endif; ?>
            <div class="ac-bar-container">
                <span class="ac-label-left"><?php echo esc_html( $args['label_left'] ); ?></span>
                <span class="ac-label-right"><?php echo esc_html( $args['label_right'] ); ?></span>
				<?php if ( $percentage ) : ?>
                    <div class="ac-bar" style="width:<?php echo esc_attr( $percentage ); ?>%"></div>
				<?php endif; ?>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}

	public function more( $array, $number = 10, $glue = ', ' ) {
		$first_set = array_slice( $array, 0, $number );
		$last_set = array_slice( $array, $number );

		ob_start();

		if ( $first_set ) {

			echo implode( $glue, $first_set );

			if ( $last_set ) { ?>
                <span class="ac-more-link-show">( <a><?php printf( __( 'Show %s more', 'codepress-admin-columns' ), count( $last_set ) ); ?></a> )</span>
                <span class="ac-show-more-block">
					<?php echo $glue . implode( $glue, $first_set ); ?>
                    <br/>
                    <span class="ac-more-link-hide">( <a><?php _e( 'Hide', 'codepress-admin-columns' ); ?></a> )</span>
                </span>
				<?php
			}
		}

		return ob_get_clean();
	}

	/**
     * Return round HTML span
     *
	 * @param $string
	 *
	 * @return string
	 */
	public function rounded( $string ) {
        return '<span class="ac-rounded">' . $string . '</span>';
	}

}