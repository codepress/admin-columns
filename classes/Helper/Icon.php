<?php

namespace AC\Helper;

class Icon {

	public function dashicon( $args = array() ) {
		$defaults = array(
			'icon'    => '',
			'title'   => '',
			'class'   => '',
			'tooltip' => '',
		);

		$data = (object) wp_parse_args( $args, $defaults );

		$class = 'dashicons dashicons-' . $data->icon;

		if ( $data->class ) {
			$class .= ' ' . trim( $data->class );
		}

		$attributes = array();

		if ( $data->title ) {
			$attributes[] = 'title="' . esc_attr( $data->title ) . '"';
		}

		if ( $data->tooltip ) {
			$attributes[] = ac_helper()->html->get_tooltip_attr( $data->tooltip );
		}

		return '<span class="' . esc_attr( $class ) . '" ' . implode( ' ', $attributes ) . '></span>';
	}

	/**
	 * @since 3.0
	 *
	 * @param string $tooltip
	 * @param string $title
	 * @param string $class
	 *
	 * @return string
	 */
	public function yes( $tooltip = false, $title = true, $class = 'green' ) {
		if ( true === $title ) {
			$title = __( 'Yes' );
		}

		return $this->dashicon( array( 'icon' => 'yes', 'class' => $class, 'title' => $title, 'tooltip' => $tooltip ) );
	}

	/**
	 * @since 3.0
	 * @return string
	 */
	public function no( $tooltip = false, $title = true, $class = 'red' ) {
		if ( true === $title ) {
			$title = __( 'No' );
		}

		return $this->dashicon( array( 'icon' => 'no-alt', 'class' => $class, 'title' => $title, 'tooltip' => $tooltip ) );
	}

	/**
	 * @since 3.0
	 *
	 * @param bool $display
	 *
	 * @return string HTML Dashicon
	 */
	public function yes_or_no( $is_true, $tooltip = '' ) {
		return $is_true ? $this->yes( $tooltip ) : $this->no( $tooltip );
	}

}