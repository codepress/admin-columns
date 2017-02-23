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

		return '<span class="dashicons dashicons-' . $data->icon . ' ' . esc_attr( trim( $data->class ) ) . '"' . ( $data->title ? ' title="' . esc_attr( $data->title ) . '"' : '' ) . '' . ( $data->tooltip ? 'data-tip="' . esc_attr( $data->tooltip ) . '"' : '' ) . '></span>';
	}

	/**
	 * @since NEWVERSION
	 * @return string
	 */
	public function yes( $tooltip = false, $title = true ) {
		if ( true === $title ) {
			$title = __( 'Yes' );
		}

		return self::dashicon( array( 'icon' => 'yes', 'class' => 'green', 'title' => $title, 'tooltip' => $tooltip ) );
	}

	/**
	 * @since NEWVERSION
	 * @return string
	 */
	public function no( $tooltip = false, $title = true ) {
		if ( true === $title ) {
			$title = __( 'No' );
		}

		return self::dashicon( array( 'icon' => 'no', 'class' => 'red', 'title' => $title, 'tooltip' => $tooltip ) );
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param bool $display
	 *
	 * @return string HTML Dashicon
	 */
	public function yes_or_no( $is_true, $tooltip = '' ) {
		return $is_true ? self::yes( $tooltip ) : self::no( $tooltip );
	}

}