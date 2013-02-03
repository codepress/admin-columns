<?php

/**
 * Post Custom Field column
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Custom_Field extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 	= 'column-meta';
		$this->properties['label']	 	= __( 'Custom Field', CPAC_TEXTDOMAIN );
		$this->properties['classes']	= 'cpac-box-metafield';		
		
		// define additional options
		$this->options['field']			= '';
		$this->options['field_type']	= '';
		$this->options['before']		= '';
		$this->options['after']			= '';
		
		// image
		$this->options['image_size']	= '';
		$this->options['image_size_w']	= 80;
		$this->options['image_size_h']	= 80;
		
		// define properties
		$this->properties['label']	 		= __( 'Custom Field', CPAC_TEXTDOMAIN );
		$this->properties['classes']	 	= 'cpac-box-metafield';		
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * Get Custom FieldType Options
	 *
	 * @since 1.5.0
	 *
	 * @return array Customfield types.
	 */
	public function get_custom_field_types() {
	
		$custom_field_types = array(
			''				=> __( 'Default'),
			'image'			=> __( 'Image', CPAC_TEXTDOMAIN ),
			'library_id'	=> __( 'Media Library', CPAC_TEXTDOMAIN ),
			'excerpt'		=> __( 'Excerpt'),
			'array'			=> __( 'Multiple Values', CPAC_TEXTDOMAIN ),
			'numeric'		=> __( 'Numeric', CPAC_TEXTDOMAIN ),
			'date'			=> __( 'Date', CPAC_TEXTDOMAIN ),
			'title_by_id'	=> __( 'Post Title (Post ID\'s)', CPAC_TEXTDOMAIN ),
			'user_by_id'	=> __( 'Username (User ID\'s)', CPAC_TEXTDOMAIN ),
			'checkmark'		=> __( 'Checkmark (true/false)', CPAC_TEXTDOMAIN ),
			'color'			=> __( 'Color', CPAC_TEXTDOMAIN ),
		);

		return apply_filters( 'cpac_field_types', $custom_field_types );
	}
	
	/**
	 * Get Title by ID
	 *
	 * @since 1.3.0
	 *
	 * @param string $meta
	 * @return string Titles
	 */
	protected function get_titles_by_id( $meta ) {
		//remove white spaces and strip tags
		$meta = CPAC_Utility::strip_trim( str_replace( ' ','', $meta ) );
		// var
		$ids = $titles = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false )
			$ids = explode( ',', $meta );
		elseif ( is_numeric( $meta ) )
			$ids[] = $meta;

		// display title with link
		if ( $ids && is_array( $ids ) ) {
			foreach ( $ids as $id ) {
				$title = is_numeric( $id ) ? get_the_title( $id ) : '';
				$link  = get_edit_post_link( $id );
				if ( $title )
					$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
			}
		}

		return implode('<span class="cpac-divider"></span>', $titles);
	}

	/**
	 * Get Users by ID
	 *
	 * @since 1.4.6.3
	 *
	 * @param string $meta
	 * @return string Users
	 */
	protected function get_users_by_id( $meta )
	{
		//remove white spaces and strip tags
		$meta = CPAC_Utility::strip_trim( str_replace( ' ', '', $meta ) );

		// var
		$ids = $names = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false ) {
			$ids = explode( ',',$meta );
		}
		elseif ( is_numeric( $meta ) ) {
			$ids[] = $meta;
		}

		// display username
		if ( $ids && is_array( $ids ) ) {
			foreach ( $ids as $id ) {
				if ( ! is_numeric( $id ) )
					continue;

				$userdata = get_userdata( $id );
				if ( is_object( $userdata ) && ! empty( $userdata->display_name ) ) {
					$names[] = $userdata->display_name;
				}
			}
		}

		return implode( '<span class="cpac-divider"></span>', $names );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 *
	 * @todo image size
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
		
		// rename hidden custom fields to their original name
		$field = substr( $this->options->field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden', '', $this->options->field ) : $this->options->field;
		
		// get metadata
		$meta = get_metadata( $this->storage_model->key, $post_id, $this->options->field, true );
		
		// implode meta array
		if ( ( 'array' == $this->options->field_type && is_array( $meta ) ) || is_array( $meta ) ) {
			$meta = $this->recursive_implode( ', ', $meta);
		}
		
		if ( ! is_string( $meta ) )	
			return false;
		
		switch ( $this->options->field_type ) :			

			case "image" :
			case "library_id" :
				$meta = $this->get_thumbnails( $meta );
				break;
	
	
			// @todo: excerpt length
			case "excerpt" :
				$meta = $this->get_shortened_string( $meta, $excerpt_length = 30 );
				break;

			case "date" :
				$meta = $this->get_date( $meta );
				break;

			case "title_by_id" :
				$titles = $this->get_titles_by_id( $meta );
				if ( $titles )
					$meta = $titles;
				break;

			case "user_by_id" :
				$names = $this->get_users_by_id( $meta );
				if ( $names )
					$meta = $names;		
				break;

			case "checkmark" :
				$checkmark = $this->get_asset_image( 'checkmark.png' );
				
				if ( empty($meta) || 'false' === $meta || '0' === $meta ) {
					$checkmark = '';
				}
				
				$meta = $checkmark;				
				break;

			case "color" :
				if ( !empty($meta) ) {
					$meta = "<div class='cpac-color'><span style='background-color:{$meta}'></span>{$meta}</div>";
				}
				break;
			
		endswitch;		
		
		// add before and after string
		if ( $meta ) {
			$meta = "{$this->options->before}{$meta}{$this->options->after}";
		}
		
		return $meta;
	}	
	
	/**
	 * Display Settings
	 *
	 * @since 2.0.0
	 */
	function display_settings() {
				
		?>
		
		<tr class="column_field">			
			<?php $this->label_view( __( "Custom Field", CPAC_TEXTDOMAIN ), '', 'field' ); ?>
			<td class="input">				
			
				<?php if ( $meta_keys = $this->storage_model->get_meta_keys() ) : ?>				
				<select name="<?php $this->attr_name( 'field' ); ?>" id="<?php $this->attr_id( 'field' ); ?>">				
				<?php foreach ( $meta_keys as $field ) : ?>
					<option value="<?php echo $field ?>"<?php selected( $field, $this->options->field ) ?>><?php echo substr( $field, 0, 10 ) == "cpachidden" ? str_replace('cpachidden','', $field ) : $field; ?></option>
				<?php endforeach; ?>				
				</select>
				<?php else : ?>
					<?php _e( 'No custom fields available.', CPAC_TEXTDOMAIN ); ?>
				<?php endif; ?>
				
			</td>
		</tr>
		
		<tr class="column_field_type">			
			<?php $this->label_view( __( "Field Type", CPAC_TEXTDOMAIN ), '', 'field_type' ); ?>			
			<td class="input">
				<select name="<?php $this->attr_name( 'field_type' ); ?>" id="<?php $this->attr_id( 'field_type' ); ?>">
				<?php foreach ( $this->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
					<option value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $this->options->field_type ) ?>><?php echo $fieldtype; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>	
		
		<?php 
		/**
		 * Add Preview size
		 *
		 */		
		$is_hidden = 'image' == $this->options->field_type ? false : true;
		
		$this->display_field_preview_size( $is_hidden ); 
		?>	
		
		<tr class="column_before">		
			<?php $this->label_view( __( "Before", CPAC_TEXTDOMAIN ), __( 'This text will appear before the custom field value.', CPAC_TEXTDOMAIN ), 'before' ); ?>			
			<td class="input">
				<input type="text" class="cpac-before" name="<?php $this->attr_name( 'before' ); ?>" id="<?php $this->attr_id( 'before' ); ?>" value="<?php echo $this->options->before; ?>"/>
			</td>
		</tr>
		<tr class="column_after">			
			<?php $this->label_view( __( "After", CPAC_TEXTDOMAIN ), __( 'This text will appear after the custom field value.', CPAC_TEXTDOMAIN ), 'after' ); ?>
			<td class="input">
				<input type="text" class="cpac-after" name="<?php $this->attr_name( 'after' ); ?>" id="<?php $this->attr_id( 'after' ); ?>" value="<?php echo $this->options->after; ?>"/>
			</td>
		</tr>		
		<?php 
	}
}