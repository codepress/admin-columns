<?php
/**
 * CPAC_Column_Post_Status
 *
 * @since 2.0
 */
class CPAC_Column_Post_Status extends CPAC_Column {

	private $statuses;

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-status';
		$this->properties['label'] = __( 'Status', 'codepress-admin-columns' );
	}

	public function get_status( $name ) {
		$stati = $this->get_statuses();
		return isset( $stati[ $name ] ) ? $stati[ $name ] : false;
	}

	/**
	 * Get Statuses
	 */
	public function get_statuses() {
		if ( empty( $this->statuses ) ) {
			global $wp_post_statuses;
			foreach ( $wp_post_statuses as $k => $status ) {
				$this->statuses[ $k ] = $status->label;
			}
		}
		return $this->statuses;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {

		$statuses = $this->get_statuses();
		if ( isset( $statuses['future'] ) ) {
			$statuses['future'] .= " <p class='description'>" . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) , strtotime( get_post_field( 'post_date', $post_id ) ) ) . "</p>";
		}
		$post_status = $this->get_raw_value( $post_id );
		return isset( $statuses[ $post_status ] ) ? $statuses[ $post_status ] : '';
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_status', $post_id );
	}
}