<?php

class AC_Helper_Icon {

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
			$attributes[] = 'data-tip="' . esc_attr( $data->tooltip ) . '"';
		}

		return '<span class="' . esc_attr( $class ) . '" ' . implode( ' ', $attributes ) . '></span>';
	}

	/**
	 * @since 3.0
	 * @return string
	 */
	public function yes( $tooltip = false, $title = true ) {
		if ( true === $title ) {
			$title = __( 'Yes' );
		}

		return $this->dashicon( array( 'icon' => 'yes', 'class' => 'green', 'title' => $title, 'tooltip' => $tooltip ) );
	}

	/**
	 * @since 3.0
	 * @return string
	 */
	public function no( $tooltip = false, $title = true ) {
		if ( true === $title ) {
			$title = __( 'No' );
		}

		return $this->dashicon( array( 'icon' => 'no', 'class' => 'red', 'title' => $title, 'tooltip' => $tooltip ) );
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