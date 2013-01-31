<?php

/**
 * Post Custom Field column
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Custom_Field extends CPAC_Column_Post {

	function __construct( $storage_key, $column_name ) {		
				
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
		$this->properties['column_name'] 	= 'column-meta';
		$this->properties['type_label']	 	= __( 'Custom Field', CPAC_TEXTDOMAIN );
		$this->properties['classes']	 	= 'cpac-box-metafield';
		$this->properties['is_cloneable'] 	= true;
		
		parent::__construct( $storage_key, $column_name );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 *
	 * @todo image size
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {
	
	}
	
	/**
	 * @see CPAC_Column::get_sortable_vars()
	 * @since 2.0.0
	 */
	function get_sortable_vars( $vars, $posts = array() ) {		
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
	 * Display Settings
	 *
	 * @since 2.0.0
	 */
	function display_settings() {
		
		// only display if there are meta keys available
		if ( ! $this->get_meta_keys() )
			return false;
		
		?>			
		<tr class="column_field">			
			<?php $this->label_view( __( "Custom Field", CPAC_TEXTDOMAIN ), '', 'field' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'field' ); ?>" id="<?php $this->attr_id( 'field' ); ?>">
				
				<?php foreach ( $this->get_meta_keys( $this->options->storage_key ) as $field ) : ?>
					<option value="<?php echo $field ?>"<?php selected( $field, $this->options->field ) ?>><?php echo substr( $field, 0, 10 ) == "cpachidden" ? str_replace('cpachidden','', $field ) : $field; ?></option>
				<?php endforeach; ?>
				
				</select>
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