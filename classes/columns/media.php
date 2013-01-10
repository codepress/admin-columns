<?php

class cpac_columns_media extends cpac_columns
{
	public function __construct()
    {
		$this->type = 'wp-media';
    }

	/**
	 * 	Get WP default media columns.
	 *
	 * 	@since     1.2.1
	 */
	function get_default_columns()
	{
		// You can use this filter to add third_party columns by hooking into this.
		do_action( 'cpac-get-default-columns-media' );

		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');

		// As of WP Release 3.5 we can use the following.
		if ( version_compare( get_bloginfo('version'), '3.4.10', '>=' ) ) {

			$table 		= new WP_Media_List_Table(array( 'screen' => 'upload' ));
			$columns 	= $table->get_columns();
		}

		// WP versions older then 3.5
		// @todo: make this deprecated
		else {

			global $current_screen;

			// save original
			$org_current_screen = $current_screen;

			// prevent php warning
			if ( !isset($current_screen) ) $current_screen = new stdClass;

			// overwrite current_screen global with our media id...
			$current_screen->id = 'upload';

			// init media class
			$wp_media = new WP_Media_List_Table;

			// get media columns
			$columns = $wp_media->get_columns();

			// reset current screen
			$current_screen = $org_current_screen;
		}

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		return apply_filters('cpac-default-media-columns', $columns);
	}

	/**
	 * Custom media columns
	 *
	 * @since     1.3
	 */
	function get_custom_columns()
	{
		$custom_columns = array(
			'column-mediaid' => array(
				'label'	=> __('ID', CPAC_TEXTDOMAIN)
			),
			'column-mime_type' => array(
				'label'	=> __('Mime type', CPAC_TEXTDOMAIN)
			),
			'column-file_name' => array(
				'label'	=> __('File name', CPAC_TEXTDOMAIN)
			),
			'column-dimensions' => array(
				'label'	=> __('Dimensions', CPAC_TEXTDOMAIN)
			),
			'column-height' => array(
				'label'	=> __('Height', CPAC_TEXTDOMAIN)
			),
			'column-width' => array(
				'label'	=> __('Width', CPAC_TEXTDOMAIN)
			),
			'column-caption' => array(
				'label'	=> __('Caption', CPAC_TEXTDOMAIN)
			),
			'column-description' => array(
				'label'	=> __('Description', CPAC_TEXTDOMAIN)
			),
			'column-alternate_text' => array(
				'label'	=> __('Alt', CPAC_TEXTDOMAIN)
			),
			'column-file_paths' => array(
				'label'	=> __('Upload paths', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'enable_sorting' => false
				)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'enable_sorting' => false
				)
			),
			'column-filesize' => array(
				'label'	=> __('File size', CPAC_TEXTDOMAIN)
			)
		);

		// Get extended image metadata, exif or iptc as available.
		// uses exif_read_data()
		if ( function_exists('exif_read_data') ) {
			$custom_columns = array_merge( $custom_columns, array(
				'column-image-aperture' => array(
					'label'		=> __('Aperture', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Aperture EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-credit' => array(
					'label'		=> __('Credit', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Credit EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-camera' => array(
					'label'		=> __('Camera', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Camera EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-caption' => array(
					'label'		=> __('Caption', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Caption EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-created_timestamp' => array(
					'label'		=> __('Timestamp', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Timestamp EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-copyright' => array(
					'label'		=> __('Copyright', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Copyright EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-focal_length' => array(
					'label'		=> __('Focal Length', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Focal Length EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-iso' => array(
					'label'		=> __('ISO', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('ISO EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-shutter_speed' => array(
					'label'		=> __('Shutter Speed', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Shutter Speed EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-title' => array(
					'label'		=> __('Title', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Title EXIF', CPAC_TEXTDOMAIN)
					)
				)
			));
		}

		// Custom Field support
		if ( $this->get_meta_keys() ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __('Custom Field', CPAC_TEXTDOMAIN),
				'field'			=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __('Custom Field', CPAC_TEXTDOMAIN),
					'class'			=> 'cpac-box-metafield'
				)
			);
		}

		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);

		return apply_filters('cpac-custom-media-columns', $custom_columns);
	}

	/**
     * Get Meta Keys
     *
	 * @since 1.5
     */
    public function get_meta_keys()
    {
        global $wpdb;

        $fields = $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1", ARRAY_N );

		if ( is_wp_error( $fields ) )
			$fields = false;

		return apply_filters( 'cpac-get-meta-keys-media', $this->maybe_add_hidden_meta($fields) );
    }

	/**
	 * Get Label
	 *
	 * @since 1.5
	 */
	function get_label()
	{
		return __('Media Library');
	}
}