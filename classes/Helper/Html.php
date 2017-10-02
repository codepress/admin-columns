<?php

class AC_Helper_Html {

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @since 3.0
	 * @return string
	 */
	public function get_attribute_as_string( $key, $value ) {
		return sprintf( '%s="%s"', $key, esc_attr( trim( $value ) ) );
	}

	/**
	 * @param array $attributes
	 *
	 * @since 3.0
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

		if ( array_key_exists( 'tooltip', $attributes ) ) {
			$attributes['data-ac-tip'] = $attributes['tooltip'];
			unset( $attributes['tooltip'] );
		}

		$allowed = wp_allowed_protocols();
		$allowed[] = 'skype';
		$allowed[] = 'call';

		return '<a href="' . esc_url( $url, $allowed ) . '" ' . $this->get_attributes( $attributes ) . '>' . $label . '</a>';
	}

	/**
	 * @since 2.5
	 */
	public function divider() {
		return '<span class="ac-divider"></span>';
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function get_tooltip_attr( $content ) {
		if ( ! $content ) {
			return false;
		}

		return 'data-ac-tip="' . esc_attr( $content ) . '"';
	}

	/**
	 * @param $label
	 * @param $tooltip
	 *
	 * @return string
	 */
	public function tooltip( $label, $tooltip, $attributes = array() ) {
		if ( $label && $tooltip ) {
			$label = '<span ' . $this->get_tooltip_attr( $tooltip ) . $this->get_attributes( $attributes ) . '>' . $label . '</span>';
		}

		return $label;
	}

	/**
	 * Displays a toggle Box.
	 *
	 * @param string $label
	 * @param string $contents
	 */
	public function toggle_box( $label, $contents ) {
		if ( ! $label ) {
			return;
		}

		if ( $contents ) : ?>
			<a class="ac-toggle-box-link" href="#"><?php echo $label; ?></a>
			<div class="ac-toggle-box-contents"><?php echo $contents; ?></div>
			<?php
		else :
			echo $label;
		endif;
	}

	/**
	 * Display a toggle box which trigger an ajax event on click. The ajax callback calls AC_Column::get_ajax_value.
	 *
	 * @param int    $id
	 * @param string $label
	 * @param string $column_name
	 *
	 * @return string
	 */
	public function get_ajax_toggle_box_link( $id, $label, $column_name ) {
		return ac_helper()->html->link( '#', $label . '<div class="spinner"></div>', array(
			'class'              => 'ac-toggle-box-link',
			'data-column'        => $column_name,
			'data-item-id'       => $id,
			'data-ajax-populate' => 1,
		) );
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
			if ( in_array( $attribute, array( 'title', 'id', 'class', 'style', 'target', 'rel', 'download' ) ) || 'data-' === substr( $attribute, 0, 5 ) ) {
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

	/**
	 * Returns star rating based on X start from $max count. Does support decimals.
	 *
	 * @param int $count
	 * @param int $max
	 *
	 * @return string
	 */
	public function stars( $count, $max = 0 ) {
		$stars = array(
			'filled' => floor( $count ),
			'half'   => floor( round( ( $count * 2 ) ) - ( floor( $count ) * 2 ) ) ? 1 : 0,
			'empty'  => 0,
		);

		$max = absint( $max );

		if ( $max > 0 ) {
			$star_count = $stars['filled'] + $stars['half'];

			$stars['empty'] = $max - $star_count;

			if ( $star_count > $max ) {
				$stars['filled'] = $max;
				$stars['half'] = 0;
			}
		}

		$icons = array();

		foreach ( $stars as $type => $count ) {
			for ( $i = 1; $i <= $count; $i++ ) {
				$icons[] = ac_helper()->icon->dashicon( array( 'icon' => 'star-' . $type, 'class' => 'ac-value-star' ) );
			}
		}

		ob_start();
		?>
		<span class="ac-value-stars">
			<?php echo implode( ' ', $icons ); ?>
		</span>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param string $value HTML
	 * @param int    $removed
	 *
	 * @return string
	 */
	public function images( $value, $removed = false ) {
		if ( $removed ) {
			$value .= ac_helper()->html->rounded( '+' . $removed );
		}

		return '<div class="ac-image-container">' . $value . '</div>';
	}

}