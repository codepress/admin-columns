<?php
/**
 * CPAC_Column_Media_File_Size
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Exif_Data extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 = 'column-exif-data';
		$this->properties['label']	 = __( 'EXIF data', CPAC_TEXTDOMAIN );
		
		// define options
		$this->options['exif_datatype']	 = '';
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * Get EXIF data
	 *
	 * Get extended image metadata
	 *
	 * @since 2.0.0
	 *
	 * @return array EXIF data types
	 */
	private function get_exif_types() {
		
		$exif_types = array(
			'aperture'			=> __( 'Aperture', CPAC_TEXTDOMAIN ),
			'credit'			=> __( 'Credit', CPAC_TEXTDOMAIN ),
			'camera'			=> __( 'Camera', CPAC_TEXTDOMAIN ),
			'caption'			=> __( 'Caption', CPAC_TEXTDOMAIN ),
			'created_timestamp'	=> __( 'Timestamp', CPAC_TEXTDOMAIN ),
			'copyright'			=> __( 'Copyright EXIF', CPAC_TEXTDOMAIN ),
			'focal_length'		=> __( 'Focal Length', CPAC_TEXTDOMAIN ),
			'iso'				=> __( 'ISO', CPAC_TEXTDOMAIN ),
			'shutter_speed'		=> __( 'Shutter Speed', CPAC_TEXTDOMAIN ),
			'title'				=> __( 'Title', CPAC_TEXTDOMAIN ),
		);
		
		return $exif_types;		
	}
	 
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {
		
		$value = '';
		
		$data = $this->options->exif_datatype;		
		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );
		
		if ( isset( $meta['image_meta'][ $data ] ) ) {			
			$value = $meta['image_meta'][ $data ];
			
			if ( 'created_timestamp' == $data ) {
				$value = date_i18n( get_option('date_format') . ' ' . get_option('time_format') , strtotime( $value ) );
			}			
		}
		
		return $value;		
	}
	
	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {
		
		if ( function_exists( 'exif_read_data' ) )
			return true;
		
		return false;
	}
	
	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {
				
		?>
		
		<tr class="column-exif-data">			
			<?php $this->label_view( $this->properties->label, '', 'exif_datatype' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'exif_datatype' ); ?>" id="<?php $this->attr_id( 'exif_datatype' ); ?>">				
				<?php foreach ( $this->get_exif_types() as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $key, $this->options->exif_datatype ) ?>><?php echo $label; ?></option>
				<?php endforeach; ?>				
				</select>
			</td>
		</tr>		
		<?php 
	}
}