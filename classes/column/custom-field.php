<?php

/**
 * CPAC_Column_Custom_Field
 *
 * @since 1.0
 */
class CPAC_Column_Custom_Field extends CPAC_Column {

	private $user_settings;

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 		= 'column-meta';
		$this->properties['label']	 		= __( 'Custom Field', 'cpac' );
		$this->properties['classes']		= 'cpac-box-metafield';
		$this->properties['is_cloneable']	= true;

		// define additional options
		$this->options['field']				= '';
		$this->options['field_type']		= '';
		$this->options['before']			= '';
		$this->options['after']				= '';

		$this->options['image_size']		= '';
		$this->options['image_size_w']		= 80;
		$this->options['image_size_h']		= 80;

		$this->options['excerpt_length']	= 15;

		$this->options['date_format']		= '';
		$this->options['date_save_format']	= '';

		// for retireving sorting preference
		$this->user_settings = get_option( 'cpac_general_options' );

		// call construct
		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::sanitize_options()
	 * @since 1.0
	 */
	function sanitize_options( $options ) {

		if ( empty( $options['date_format'] ) ) {
			$options['date_format'] = get_option( 'date_format' );
		}

		return $options;
	}

	/**
	 * Get Custom FieldType Options - Value method
	 *
	 * @since 1.0
	 *
	 * @return array Customfield types.
	 */
	public function get_custom_field_types() {

		$custom_field_types = array(
			''				=> __( 'Default'),
			'checkmark'		=> __( 'Checkmark (true/false)', 'cpac' ),
			'color'			=> __( 'Color', 'cpac' ),
			'count'			=> __( 'Counter', 'cpac' ),
			'date'			=> __( 'Date', 'cpac' ),
			'excerpt'		=> __( 'Excerpt'),
			'image'			=> __( 'Image', 'cpac' ),
			'library_id'	=> __( 'Media Library', 'cpac' ),
			'array'			=> __( 'Multiple Values', 'cpac' ),
			'numeric'		=> __( 'Numeric', 'cpac' ),
			'title_by_id'	=> __( 'Post Title (Post ID\'s)', 'cpac' ),
			'user_by_id'	=> __( 'Username (User ID\'s)', 'cpac' ),
		);

		// deprecated. do not use, will be removed.
		$custom_field_types = apply_filters( 'cpac_custom_field_types', $custom_field_types );

		// Filter
		$custom_field_types = apply_filters( 'cac/column/meta/types', $custom_field_types );

		return $custom_field_types;
	}

	/**
	 * Get First ID from array
	 *
	 * @since 1.0
	 *
	 * @param string $meta
	 * @return string Titles
	 */
	public function get_ids_from_meta( $meta ) {

		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace( ' ','', $meta ) );

		// var
		$ids = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false )
			$ids = explode( ',', $meta );
		elseif ( is_numeric( $meta ) )
			$ids[] = $meta;

		return $ids;
	}

	/**
	 * Get Title by ID - Value method
	 *
	 * @since 1.0
	 *
	 * @param string $meta
	 * @return string Titles
	 */
	private function get_titles_by_id( $meta ) {

		$titles = array();

		// display title with link
		if ( $ids = $this->get_ids_from_meta( $meta ) ) {
			foreach ( (array) $ids as $id ) {

				if ( ! is_numeric( $id ) ) continue;

				$link = get_edit_post_link( $id );
				if ( $title = get_the_title( $id ) )
					$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
			}
		}

		return implode('<span class="cpac-divider"></span>', $titles);
	}

	/**
	 * Get Users by ID - Value method
	 *
	 * @since 1.0
	 *
	 * @param string $meta
	 * @return string Users
	 */
	private function get_users_by_id( $meta )	{

		$names = array();

		// display username
		if ( $ids = $this->get_ids_from_meta( $meta ) ) {
			foreach ( (array) $ids as $id ) {
				if ( ! is_numeric( $id ) ) continue;

				$userdata = get_userdata( $id );
				if ( is_object( $userdata ) && ! empty( $userdata->display_name ) ) {

					// link
					$link = get_edit_user_link( $id );

					$names[] = $link ? "<a href='{$link}'>{$userdata->display_name}</a>" : $userdata->display_name;
				}
			}
		}

		return implode( '<span class="cpac-divider"></span>', $names );
	}

	/**
	 * Get meta value
	 *
	 * @since 2.0.0
	 *
	 * @param string $meta Contains Meta Value
	 * @param int $id Optional Object ID
	 * @return string Users
	 */
	function get_value_by_meta( $meta, $id = null ) {

		switch ( $this->options->field_type ) :

			case "image" :
			case "library_id" :
				$meta = implode( $this->get_thumbnails( $meta, array(
					'image_size'	=> $this->options->image_size,
					'image_size_w'	=> $this->options->image_size_w,
					'image_size_h'	=> $this->options->image_size_h,
				)));
				break;

			case "excerpt" :
				$meta = $this->get_shortened_string( $meta, $this->options->excerpt_length );
				break;

			case "date" :
				$meta = $this->get_date( $meta, $this->options->date_format );
				break;

			case "title_by_id" :
				$meta = $this->get_titles_by_id( $meta );
				break;

			case "user_by_id" :
				$meta = $this->get_users_by_id( $meta );
				break;

			case "checkmark" :
				$checkmark = $this->get_asset_image( 'checkmark.png' );

				if ( empty( $meta ) || 'false' === $meta || '0' === $meta ) {
					$checkmark = '';
				}

				$meta = $checkmark;
				break;

			case "color" :
				if ( ! empty( $meta ) ) {
					$text_color = $this->get_text_color( $meta );
					$meta = "<div class='cpac-color'><span style='background-color:{$meta};color:{$text_color}'>{$meta}</span></div>";
				}
				break;

			case "count" :
				if ( $count = $this->get_raw_value( $id, false ) )
					$meta = count( $count );
				break;

		endswitch;

		return $meta;
	}

	/**
	 * Determines text color absed on bakground coloring.
	 *
	 * @since 1.0
	 */
	function get_text_color( $bg_color ) {

		$rgb = $this->hex2rgb( $bg_color );

		return $rgb && ( ( $rgb[0]*0.299 + $rgb[1]*0.587 + $rgb[2]*0.114 ) < 186 ) ? '#ffffff' : '#333333';
	}

	/**
	 * Convert hex to rgb
	 *
	 * @since 1.0
	 */
	function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);

		return $rgb;
	}

	/**
	 * Get Field key
	 *
	 * @since 2.0.3
	 *
	 * @param string Custom Field Key
	 */
	function get_field_key() {

		return substr( $this->options->field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden', '', $this->options->field ) : $this->options->field;
	}

	/**
	 * Get meta by ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $id ID
	 * @return string Meta Value
	 */
	public function get_meta_by_id( $id ) {

		// get metadata
		$meta = $this->get_raw_value( $id );

		// try to turn any array into a comma seperated string for further use
		if ( ( 'array' == $this->options->field_type && is_array( $meta ) ) || is_array( $meta ) ) {
			$meta = $this->recursive_implode( ', ', $meta );
		}

		if ( ! is_string( $meta ) )
			return false;

		return $meta;
	}

	/**
	 * Get before value
	 *
	 * @since 1.0
	 */
	function get_before() {

		return stripslashes( $this->options->before );
	}

	/**
	 * Get after value
	 *
	 * @since 1.0
	 */
	function get_after() {

		return stripslashes( $this->options->after );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $id, $single = true ) {

		$field_key = $this->get_field_key();

		$raw_value = get_metadata( $this->storage_model->type, $id, $field_key, $single );

		return apply_filters( 'cac/column/meta/raw_value', $raw_value, $id, $field_key, $this );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 1.0
	 */
	function get_value( $id ) {

		$value = '';

		if ( $meta = $this->get_meta_by_id( $id ) ) {

			// get value by meta
			$value = $this->get_value_by_meta( $meta, $id );
		}

		$value = apply_filters( 'cac/column/meta/value', $value, $id, $this );

		$before = $this->get_before();
		$after 	= $this->get_after();

		// add before and after string
		if ( $value ) {
			$value = "{$before}{$value}{$after}";
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 1.0
	 */
	function display_settings() {

		$show_hidden_meta = isset( $this->user_settings['show_hidden'] ) && '1' === $this->user_settings['show_hidden'] ? true : false;

		?>

		<tr class="column_field">
			<?php $this->label_view( __( "Custom Field", 'cpac' ), __( "Select your custom field.", 'cpac' ), 'field' ); ?>
			<td class="input">

				<?php if ( $meta_keys = $this->storage_model->get_meta_keys( $show_hidden_meta ) ) : ?>
				<select name="<?php $this->attr_name( 'field' ); ?>" id="<?php $this->attr_id( 'field' ); ?>">
				<?php foreach ( $meta_keys as $field ) : ?>
					<option value="<?php echo $field ?>"<?php selected( $field, $this->options->field ) ?>><?php echo substr( $field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden','', $field ) : $field; ?></option>
				<?php endforeach; ?>
				</select>
				<?php else : ?>
					<?php _e( 'No custom fields available.', 'cpac' ); ?>
				<?php endif; ?>

			</td>
		</tr>

		<tr class="column_field_type">
			<?php $this->label_view( __( "Field Type", 'cpac' ), __( 'This will determine how the value will be displayed.', 'cpac' ), 'field_type' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'field_type' ); ?>" id="<?php $this->attr_id( 'field_type' ); ?>">
				<?php foreach ( $this->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
					<?php
					$display_option = '';
					if ( in_array( $fieldkey, array( 'date' ) ) ) 					$display_option = 'date';
					if ( in_array( $fieldkey, array( 'image', 'library_id' ) ) ) 	$display_option = 'image_size';
					if ( in_array( $fieldkey, array( 'excerpt' ) ) ) 				$display_option = 'excerpt';
					?>
					<option data-display-option="<?php echo $display_option; ?>" value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $this->options->field_type ) ?>><?php echo $fieldtype; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<?php

		/**
		 * Add Date Format
		 *
		 */
		$is_hidden = in_array( $this->options->field_type, array( 'date' ) ) ? false : true;
		$this->display_field_date_format( $is_hidden );

		/**
		 * Add Preview size
		 *
		 */
		$is_hidden = in_array( $this->options->field_type, array( 'image', 'library_id' ) ) ? false : true;
		$this->display_field_preview_size( $is_hidden );

		/**
		 * Add Excerpt length
		 *
		 */
		$is_hidden = in_array( $this->options->field_type, array( 'excerpt' ) ) ? false : true;
		$this->display_field_excerpt_length( $is_hidden );

		/**
		 * Before / After
		 *
		 */
		$this->display_field_before_after();
	}
}