<?php

class AC_Settings_Column_StatusIcon extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

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

		$view = new AC_View( array(
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

	private function get_future_date( $post_id )  {
		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( get_post_field( 'post_date', $post_id ) ) );
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

		if ( $this->get_use_icon() ) {

			switch ( $status ) {
				case 'private' :
					$value = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'hidden', 'class' => 'gray' ) ), __( 'Private' ) );
					break;
				case 'publish' :
					$value = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'yes', 'class' => 'blue large' ) ), __( 'Published' ) );
					break;
				case 'draft' :
					$value = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'edit', 'class' => 'green' ) ), __( 'Draft' ) );
					break;
				case 'pending' :
					$value = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'backup', 'class' => 'orange' ) ), __( 'Pending for review' ) );
					break;
				case 'future' :
					$value = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'clock' ) ), __( 'Scheduled' ) . ': <em>' . $this->get_future_date( $post_id ) . '</em>' );
					break;
			}

			if ( get_post_field( 'post_password', $post_id ) ) {
				$value .= ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'lock', 'class' => 'gray' ) ), __( 'Password protected' ) );
			}
		} else if ( isset( $wp_post_statuses[ $status ] ) ) {
			$value = $wp_post_statuses[ $status ]->label;

			if ( 'future' === $status ) {
				$value .= " <p class='description'>" . $this->get_future_date( $post_id ) . "</p>";
			}
		}

		return $value;
	}

}