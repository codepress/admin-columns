<?php

/**
 * CPAC_Column_Post_Status
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Status extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-status';
		$this->properties['label']	 	= __( 'Status', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$value = '';

		$post_status = $this->get_raw_value( $post_id );

		$p = get_post( $post_id );

		$builtin_status = array(
			'publish' 	=> __( 'Published', 'cpac' ),
			'draft' 	=> __( 'Draft', 'cpac' ),
			'future' 	=> __( 'Scheduled', 'cpac' ) . " <p class='description'>" . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) , strtotime( $p->post_date ) ) . "</p>",
			'private' 	=> __( 'Private', 'cpac' ),
			'pending' 	=> __( 'Pending Review', 'cpac' ),
			'auto-draft' => __( 'Auto Draft', 'cpac' ),
			'trash' 	=> __( 'Trash', 'cpac' ),
		);

		if ( isset( $builtin_status[ $post_status ] ) )
			$value = $builtin_status[ $post_status ];

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_post_field( 'post_status', $post_id );
	}
}