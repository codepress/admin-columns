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
		$this->properties['label']	 	= __( 'Status', CPAC_TEXTDOMAIN );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		
		$value = '';
		
		$p = get_post( $post_id );
		
		$builtin_status = array(
			'publish' 	=> __( 'Published', CPAC_TEXTDOMAIN ),
			'draft' 	=> __( 'Draft', CPAC_TEXTDOMAIN ),
			'future' 	=> __( 'Scheduled', CPAC_TEXTDOMAIN ) . " <p class='description'>" . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) , strtotime( $p->post_date ) ) . "</p>",
			'private' 	=> __( 'Private', CPAC_TEXTDOMAIN ),
			'pending' 	=> __( 'Pending Review', CPAC_TEXTDOMAIN ),
			'trash' 	=> __( 'Trash', CPAC_TEXTDOMAIN ),			
		);
		
		if ( isset( $builtin_status[ $p->post_status ] ) )
			$value = $builtin_status[ $post_status ];

		return $value;
	}
}