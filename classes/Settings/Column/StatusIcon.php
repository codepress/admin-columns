<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class StatusIcon extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var bool
	 */
	private $use_icon;

	protected function define_options() {
		return array( 'use_icon' => '' );
	}

	public function create_view() {

		$setting = $this->create_element( 'radio' )
		                ->set_options( array(
			                '1' => __( 'Yes' ),
			                ''  => __( 'No' ),
		                ) );

		$view = new View( array(
			'label'   => __( 'Use an icon?', 'codepress-admin-columns' ),
			'tooltip' => __( 'Use an icon instead of text for displaying the status.', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_use_icon() {
		return $this->use_icon;
	}

	/**
	 * @param int $use_icons
	 *
	 * @return bool
	 */
	public function set_use_icon( $use_icon ) {
		$this->use_icon = $use_icon;

		return true;
	}

	/**
	 * @param string $status
	 * @param int    $post_id
	 *
	 * @return string
	 */
	public function format( $status, $post_id ) {
		global $wp_post_statuses;

		$value = $status;

		$post = get_post( $post_id );

		if ( $this->get_use_icon() ) {
			$value = ac_helper()->post->get_status_icon( $post );

			if ( $post->post_password ) {
				$value .= ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'lock', 'class' => 'gray' ) ), __( 'Password protected' ) );
			}
		} else if ( isset( $wp_post_statuses[ $status ] ) ) {
			$value = $wp_post_statuses[ $status ]->label;

			if ( 'future' === $status ) {
				$value .= " <p class='description'>" . ac_helper()->date->date( $post->post_date, 'wp_date_time' ) . "</p>";
			}
		}

		return $value;
	}

}