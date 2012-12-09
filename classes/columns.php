<?php

class cpac_columns
{
	public $type;
	
	/**
	 * Get a list of Column options per post type
	 *
	 * @since     1.0
	 */
	public function get_column_boxes() 
	{		
		$type = $this->type;
		
		// merge all columns
		$display_columns 	= self::get_merged_columns( $type );
		
		// define
		$list = '';	
		
		// loop throught the active columns
		if ( $display_columns ) {
			foreach ( $display_columns as $id => $values ) {		
				
				$classes = array();

				// set state
				$state 	= isset($values['state']) ? $values['state'] : '';
				
				// class
				$classes[] = "cpac-box-{$id}";
				if ( $state ) {
					$classes[] = 'active';
				}
				if ( ! empty($values['options']['class']) ) {
					$classes[] = $values['options']['class'];
				}
				$class = implode(' ', $classes);
					
				// more box options	
				$more_options 	= $this->get_additional_box_options($this->type, $id, $values);
				$action 		= "<a class='cpac-action' href='#open'>open</a>";
						
				// type label
				$type_label = isset($values['options']['type_label']) ? $values['options']['type_label'] : '';
				
				// label
				$label = isset($values['label']) ? str_replace("'", '"', $values['label']) : '';
				
				// main label
				$main_label = $values['label'];	
				
				// main label exception for comments
				if ( 'comments' == $id ) {
					$main_label = cpac_static::get_comment_icon();
				}
				
				// width
				$width			= isset($values['width']) ? $values['width'] : 0;
				$width_descr	= isset($values['width']) && $values['width'] > 0 ? $values['width'] . '%' : __('default', CPAC_TEXTDOMAIN);
				
				// hide box options
				$label_hidden = '';
				if ( ! empty($values['options']['hide_options']) || strpos($label, '<img') !== false ) {
					$label_hidden = ' style="display:none"';
				}
				
				$list .= "
					<li class='{$class}'>
						<div class='cpac-sort-handle'></div>
						<div class='cpac-type-options'>					
							<div class='cpac-checkbox'></div>
							<input type='hidden' class='cpac-state' name='cpac_options[columns][{$type}][{$id}][state]' value='{$state}'/>
							<label class='main-label'>{$main_label}</label>
						</div>
						<div class='cpac-meta-title'>
							{$action}
							<span>{$type_label}</span>
						</div>
						<div class='cpac-type-inside'>				
							<label for='cpac_options-{$type}-{$id}-label'{$label_hidden}>Label: </label>
							<input type='text' name='cpac_options[columns][{$type}][{$id}][label]' id='cpac_options-{$type}-{$id}-label' value='{$label}' class='text'{$label_hidden}/>
							<label for='cpac_options-{$type}-{$id}-width'>" . __('Width', CPAC_TEXTDOMAIN) . ":</label>			
							<input type='hidden' maxlength='4' class='input-width' name='cpac_options[columns][{$type}][{$id}][width]' id='cpac_options-{$type}-{$id}-width' value='{$width}' />
							<div class='description width-decription' title='" . __('default', CPAC_TEXTDOMAIN) . "'>{$width_descr}</div>
							<div class='input-width-range'></div>
							<br/>
							{$more_options}
						</div>
					</li>
				";			
			}
		}
		
		// custom field button
		$button_add_column = '';
		if ( $this->get_meta_by_type($type) )
			$button_add_column = "<a href='javacript:;' class='cpac-add-customfield-column button'>+ " . __('Add Custom Field Column', CPAC_TEXTDOMAIN) . "</a>";
		
		return "
			<div class='cpac-box'>
				<ul class='cpac-option-list'>
					{$list}			
				</ul>
				{$button_add_column}
				<div class='cpac-reorder-msg'>" . __('drag and drop to reorder', CPAC_TEXTDOMAIN) . "</div>		
			</div>
			";
	}
	
	/**
	 * Get merged columns
	 *
	 * @since     1.0
	 */
	function get_merged_columns() 
	{
		// get added and WP columns
		$wp_default_columns = $this->get_default_columns();
		$wp_added_columns  	= $this->get_added_columns();
		
		// merge
		$default_columns	= wp_parse_args($wp_added_columns, $wp_default_columns);
		
		// Gget saved columns
		if ( ! $db_columns = cpac_static::get_stored_columns( $this->type ) )
			return $default_columns;
			
		// let's remove any unavailable columns.. such as disabled plugins			
		$diff = array_diff( array_keys($db_columns), array_keys($default_columns) );
		
		// check for differences
		if ( ! empty($diff) && is_array($diff) ) {						
			foreach ( $diff as $column_name ){				
				// make an exception for column-meta-xxx
				if ( ! cpac_static::is_column_meta($column_name) ) {
					unset($db_columns[$column_name]);
				}
			}
		}	
		
		// loop throught the active columns
		foreach ( $db_columns as $id => $values ) {
		
			// get column meta options from custom columns
			if ( cpac_static::is_column_meta($id) && !empty($wp_custom_columns['column-meta-1']['options']) ) {					
				$db_columns[$id]['options'] = $wp_custom_columns['column-meta-1']['options'];			
			}
			
			// add static options
			elseif ( isset($default_columns[$id]['options']) )
				$db_columns[$id]['options'] = $default_columns[$id]['options'];
			
			unset($default_columns[$id]);			
		}
		
		// merge all
		return wp_parse_args($db_columns, $default_columns);
	}		
		
	/**
	 * Build uniform format for all columns
	 *
	 * @since     1.0
	 */
	public function get_uniform_format($columns) 
	{
		// we remove the checkbox column as an option... 
		if ( isset($columns['cb']) )
			unset($columns['cb']);
		
		// change to uniform format
		$uniform_columns = array();
		foreach ( (array) $columns as $id => $label ) {
			$hide_options 	= false;
			$type_label 	= $label;
			
			// comment exception		
			if ( 'comments' == $id ) {					
				$label 			= '';
				$type_label 	= __('Comments', CPAC_TEXTDOMAIN);
				$hide_options 	= true;
			}
			
			// user icon exception
			if ( $id == 'icon' ) {
				$type_label 	= __('Icon', CPAC_TEXTDOMAIN);
			}
			
			$uniform_columns[$id] = array(
				'label'			=> $label,
				'state'			=> 'on',
				'options'		=> array(
					'type_label' 	=> $type_label,
					'hide_options'	=> $hide_options,
					'class'			=> 'cpac-box-wp-native',
				)
			);
		}
		return $uniform_columns;
	}
	
	
	
	/**
	 * Parse defaults
	 *
	 * @since     1.1
	 */
	public function parse_defaults($columns) 
	{
		// default arguments
		$defaults = array(	
			
			// stored values
			'label'			=> '', // custom label
			'state' 		=> '', // display state
			'width' 		=> '', // column width			
			
			// static values
			'options'		=> array(				
				'type_label'	=> __('Custom', CPAC_TEXTDOMAIN),
				'hide_options'	=> false,
				'class'			=> 'cpac-box-custom',
				'sortorder'		=> 'on',
			)
		);
		
		// parse args
		foreach ( $columns as $k => $column ) {
			$c[$k] = wp_parse_args( $column, $defaults);
			
			// parse options args
			if ( isset($column['options']) )
				$c[$k]['options'] = wp_parse_args( $column['options'], $defaults['options']);
				
			// set type label
			if ( empty($column['options']['type_label']) && !empty($column['label']) )
				$c[$k]['options']['type_label']	= $column['label'];
		}
		
		return $c;
	}	
	
	/**
	 * Get additional box option fields
	 *
	 * @since     1.0
	 */
	function get_additional_box_options($type, $id, $values) 
	{
		$fields = '';
		
		// Custom Fields
		if( cpac_static::is_column_meta($id) ) {
			$fields = $this->get_box_options_customfields($type, $id, $values);
		}
			
		// Author Fields
		if( 'column-author-name' == $id) {
			$fields = $this->get_box_options_author($type, $id, $values);
		}
		
		return $fields;
	}

	/**
	 * Box Options: Custom Fields
	 *
	 * @since     1.0
	 */
	function get_box_options_customfields($type, $id, $values) 
	{
		// get post meta fields	
		$fields = $this->get_meta_by_type($type);
		
		if ( empty($fields) ) 
			return false;
		
		// set meta field options
		$current = ! empty($values['field']) ? $values['field'] : '' ;
		$field_options = '';
		foreach ($fields as $field) {
			
			$field_options .= sprintf
			(
				'<option value="%s"%s>%s</option>',
				$field,
				$field == $current? ' selected="selected"':'',
				
				// change label on hidden fields
				substr($field,0,10) == "cpachidden" ? str_replace('cpachidden','',$field) : $field		
			);		
		}
		
		// set meta fieldtype options
		$currenttype = ! empty($values['field_type']) ? $values['field_type'] : '' ;
		$fieldtype_options = '';
		$fieldtypes = array(
			''				=> __('Default'),
			'image'			=> __('Image'),
			'library_id'	=> __('Media Library Icon', CPAC_TEXTDOMAIN),
			'excerpt'		=> __('Excerpt'),
			'array'			=> __('Multiple Values', CPAC_TEXTDOMAIN),
			'numeric'		=> __('Numeric', CPAC_TEXTDOMAIN),
			'date'			=> __('Date', CPAC_TEXTDOMAIN),
			'title_by_id'	=> __('Post Title (Post ID\'s)', CPAC_TEXTDOMAIN),
			'user_by_id'	=> __('Username (User ID\'s)', CPAC_TEXTDOMAIN),
			'checkmark'		=> __('Checkmark (true/false)', CPAC_TEXTDOMAIN),
			'color'			=> __('Color', CPAC_TEXTDOMAIN),
		);
		
		// add filter
		$fieldtypes = apply_filters('cpac-field-types', $fieldtypes );
		
		// set select options
		foreach ( $fieldtypes as $fkey => $fieldtype ) {
			$fieldtype_options .= sprintf
			(
				'<option value="%s"%s>%s</option>',
				$fkey,
				$fkey == $currenttype? ' selected="selected"':'',
				$fieldtype
			);
		}
		
		// before and after string
		$before = ! empty($values['before']) 	? $values['before'] : '' ;
		$after 	= ! empty($values['after']) 	? $values['after'] 	: '' ;
		
		if ( empty($field_options) )
			return false;
		
		// add remove button
		$remove = '<p class="remove-description description">'.__('This field can not be removed', CPAC_TEXTDOMAIN).'</p>';
		if ( $id != 'column-meta-1') {
			$remove = "
				<p>
					<a href='javascript:;' class='cpac-delete-custom-field-box'>".__('Remove')."</a>
				</p>
			";
		}
		
		$inside = "
			<label for='cpac-{$type}-{$id}-field'>".__('Custom Field', CPAC_TEXTDOMAIN).": </label>
			<select name='cpac_options[columns][{$type}][{$id}][field]' id='cpac-{$type}-{$id}-field'>{$field_options}</select>
			<br/>
			<label for='cpac-{$type}-{$id}-field_type'>".__('Field Type', CPAC_TEXTDOMAIN).": </label>
			<select name='cpac_options[columns][{$type}][{$id}][field_type]' id='cpac-{$type}-{$id}-field_type'>{$fieldtype_options}</select>
			<br/>
			<label for='cpac-{$type}-{$id}-before'>".__('Before', CPAC_TEXTDOMAIN).": </label>
			<input type='text' class='cpac-before' name='cpac_options[columns][{$type}][{$id}][before]' id='cpac-{$type}-{$id}-before' value='{$before}'/>				
			<br/>	
			<label for='cpac-{$type}-{$id}-after'>".__('After', CPAC_TEXTDOMAIN).": </label>
			<input type='text' class='cpac-after' name='cpac_options[columns][{$type}][{$id}][after]' id='cpac-{$type}-{$id}-after' value='{$after}'/>				
			<br/>		
			{$remove}
		";
		
		return $inside;
	}
	
	/**
	 * Box Options: Custom Fields
	 *
	 * @since     1.0
	 */
	function get_box_options_author($type, $id, $values) 
	{
		$options = '';
		$author_types = array(
			'display_name'		=> __('Display Name', CPAC_TEXTDOMAIN),
			'first_name'		=> __('First Name', CPAC_TEXTDOMAIN),
			'last_name'			=> __('Last Name', CPAC_TEXTDOMAIN),
			'first_last_name' 	=> __('First &amp; Last Name', CPAC_TEXTDOMAIN),
			'nickname'			=> __('Nickname', CPAC_TEXTDOMAIN),
			'username'			=> __('Username', CPAC_TEXTDOMAIN),
			'email'				=> __('Email', CPAC_TEXTDOMAIN),
			'userid'			=> __('User ID', CPAC_TEXTDOMAIN)
		);
		$currentname = ! empty($values['display_as']) ? $values['display_as'] : '' ;
		foreach ( $author_types as $k => $name ) {
			$selected = selected( $k, $currentname, false);
			$options .= "<option value='{$k}' {$selected}>{$name}</option>";
		}
		
		$inside = "
			<label for='cpac-{$type}-{$id}-display_as'>".__('Display name as', CPAC_TEXTDOMAIN).": </label>
			<select name='cpac_options[columns][{$type}][{$id}][display_as]' id='cpac-{$type}-{$id}-display_as'>
				{$options}
			</select>			
		";
		
		return $inside;
	}
	
	/**
	 * Get post meta fields by type; post(types) or users.
	 *
	 * @since     1.0
	 */
	function get_meta_by_type($type = 'post') 
	{
		global $wpdb;
		
		// enable the use of custom hidden fields
		$use_hidden_custom_fields = apply_filters('cpac_use_hidden_custom_fields', false);
		
		/** Comments */
		if ( $type == 'wp-comments') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1";
		}
		
		/** Users */
		elseif ( $type == 'wp-users') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1";
		}
		
		/** Media */
		elseif ( $type == 'wp-media') {
			$sql = $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1");
		}
		
		/** Posts */
		else {
			$sql = $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $type);
		}
		
		// run sql
		$fields = $wpdb->get_results($sql, ARRAY_N);
		
		// filter out hidden meta fields
		$meta_fields = array();
		if ( $fields ) {			
			foreach ($fields as $field) {
				
				// give hidden fields a prefix for identifaction
				if ( $use_hidden_custom_fields && substr($field[0],0,1) == "_") {
					$meta_fields[] = 'cpachidden'.$field[0];
				}
				
				// non hidden fields are saved as is
				elseif ( substr($field[0],0,1) != "_" ) {
					$meta_fields[] = $field[0];
				}	
			}			
		}
		
		if ( !empty($meta_fields) )
			return $meta_fields;
		
		return false;
	}
}
