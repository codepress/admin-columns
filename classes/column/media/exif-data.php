<?php
/**
 * CPAC_Column_Media_File_Size
 *
 * @since 2.0
 */
class CPAC_Column_Media_Exif_Data extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 		= 'column-exif_data';
		$this->properties['label']	 		= __( 'EXIF data', 'cpac' );
		$this->properties['is_cloneable']	= true;

		// Options
		$this->options['exif_datatype']	 = '';
	}

	/**
	 * Get EXIF data
	 *
	 * Get extended image metadata
	 *
	 * @since 2.0
	 *
	 * @return array EXIF data types
	 */
	private function get_exif_types() {

		$exif_types = array(
			'aperture'			=> __( 'Aperture', 'cpac' ),
			'credit'			=> __( 'Credit', 'cpac' ),
			'camera'			=> __( 'Camera', 'cpac' ),
			'caption'			=> __( 'Caption', 'cpac' ),
			'created_timestamp'	=> __( 'Timestamp', 'cpac' ),
			'copyright'			=> __( 'Copyright EXIF', 'cpac' ),
			'focal_length'		=> __( 'Focal Length', 'cpac' ),
			'iso'				=> __( 'ISO', 'cpac' ),
			'shutter_speed'		=> __( 'Shutter Speed', 'cpac' ),
			'title'				=> __( 'Title', 'cpac' ),
		);

		return $exif_types;
	}


	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {

		$value = '';

		$data = $this->options->exif_datatype;
		$meta = $this->get_raw_value( $id );

		if ( isset( $meta['image_meta'][ $data ] ) ) {
			$value = $meta['image_meta'][ $data ];

			if ( 'created_timestamp' == $data ) {
				$value = date_i18n( get_option('date_format') . ' ' . get_option('time_format') , strtotime( $value ) );
			}
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0
	 */
	public function get_raw_value( $id ) {

		return get_post_meta( $id, '_wp_attachment_metadata', true );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	public function apply_conditional() {

		return function_exists( 'exif_read_data' );
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {

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