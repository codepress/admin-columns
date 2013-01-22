<?php
/**
 * class CPAC_Filtering_Columns
 *
 * @since 2.0
 */
class CPAC_Filtering_Columns {

	function __construct() {

		// Add Dropdown
		add_action( 'restrict_manage_posts', array( $this, 'callback_restrict_posts' ) );

		// Handle Filter Requests
		add_filter( 'request', array( $this, 'handle_restrict_requests' ), 2 );
	}

	/**
	 * Create dropdown
	 *
	 * @since 2.0.0
	 *
	 * @param string $name Attribute Name
	 * @param string $label Label
	 * @param array $options Array with options
	 * @param string $selected Current item
	 * @retrun string Dropdown HTML select element
	 */
	function get_dropdown( $name, $label, $options, $selected = '' ) {

		if ( ! $name || ! $label || empty( $options ) )
			return false;

		// empty option
		$select = "<option value=''>".__( 'View all ', CPAC_TEXTDOMAIN )." {$label}</option>";

		foreach( $options as $value => $label ) {
			$select .= "<option value='" . esc_attr( $value ) . "'" . selected( $selected, $value, false )  . ">{$label}</option>";
		}

		return "<select class='postform' name='{$name}'>{$select}</select>";
	}

	/**
	 * Applies indenting markup for taxonomy dropdown
	 *
	 * @since 1.4.2
	 *
	 * @param array $array
	 * @param int $level
	 * @param array $ouput
	 * @return array Output
	 */
	private function apply_indenting_markup( $array, $level = 0, $output = array() ) {
        foreach( $array as $v ) {

            $prefix = '';
            for( $i=0; $i<$level; $i++ ) {
                $prefix .= '&nbsp;&nbsp;';
            }

            $output[$v->slug] = $prefix . $v->name;

            if ( ! empty( $v->children ) ) {
                $output = $this->apply_indenting_markup( $v->children, ( $level + 1 ), $output );
            }
        }

        return $output;
    }

	/**
	 * Indents any object as long as it has a unique id and that of its parent.
	 *
	 * @since 1.4.2
	 *
	 * @param type $array
	 * @param type $parentId
	 * @param type $parentKey
	 * @param type $selfKey
	 * @param type $childrenKey
	 * @return array Indented Array
	 */
	private function indent( $array, $parentId = 0, $parentKey = 'post_parent', $selfKey = 'ID', $childrenKey = 'children' ) {
		$indent = array();

        // clean counter
        $i = 0;

		foreach( $array as $v ) {

			if ( $v->$parentKey == $parentId ) {
				$indent[$i] = $v;
				$indent[$i]->$childrenKey = $this->indent( $array, $v->$selfKey, $parentKey, $selfKey );

                $i++;
			}
		}

		return $indent;
	}

	/**
	 * Get columns with filtering enabled.
	 *
	 * @since 2.0.0
	 *
	 * @param string $storage_key
	 * @return array Columns
	 */
	function get_columns_with_enabled_filtering( $storage_key ) {
		// get stored columns
		if( ! $columns = CPAC_Utility::get_stored_columns( $storage_key ) )
			return false;

		foreach ( $columns as $column_name => $column ){

			// check if taxonomy column is active
			if ( ! isset( $column['state'] ) || 'on' !== $column['state'] || ! isset( $column['filtering'] ) || 'on' !== $column['filtering'] ) {
				unset($columns[$column_name]);
			}
		}

		if ( empty( $columns ) )
			return false;

		return $columns;
	}

	/**
	 * Handle restrict request
	 *
	 * @since 2.0.0
	 */
	public function handle_restrict_requests( $vars ) {
		global $pagenow;

		if ( 'edit.php' != $pagenow && ! isset( $_REQUEST['cpac_custom_dropdowns'] ) || empty( $vars['post_type'] ) )
			return $vars;

		// get columns that have enabled filtering
		if ( $columns = $this->get_columns_with_enabled_filtering( $vars['post_type'] ) ) {

			foreach ( $columns as $column_name => $column ) {

				// is used for filtering?
				if( empty( $_REQUEST[$column_name] ) )
					continue;

				// set WP Query order vars
				$vars['meta_query'] = array(
					array(
						'key'	=> $column['field'],
						'value' => $_REQUEST[$column_name],
					)
				);
			}
		}

		return $vars;
	}

	/**
	 * Add taxonomy filters to posts
	 *
	 * @since 1.4.2
	 * @todo: Add support for customfield values longer then 30 characters.
	 */
	function callback_restrict_posts() {
		global $post_type_object;

		if ( ! isset( $post_type_object->name ) )
			return false;

		// get stored columns
		if( ! $columns = $this->get_columns_with_enabled_filtering( $post_type_object->name ) )
			return false;

		$dropdown_filters = '';

		foreach ( $columns as $column_name => $column ){

			$column_name_type = CPAC_Utility::get_column_name_type( $column_name );

			switch( $column_name_type ) :

				case 'column-taxonomy' :

					if ( $taxonomy_slug = CPAC_Utility::get_taxonomy_by_column_name( $column_name ) ) {

						$taxonomy	= get_taxonomy( $taxonomy_slug );
						$options	= $this->apply_indenting_markup( $this->indent( get_terms( $taxonomy_slug ), 0, 'parent', 'term_id' ) );
						$selected	= isset( $_GET[$taxonomy_slug] ) ? $_GET[$taxonomy_slug] : false;

						$dropdown_filters .= $this->get_dropdown( $taxonomy_slug, $taxonomy->name, $options, $selected );
					}

					break;

				case 'column-meta' :

					$type = new CPAC_Columns_Posttype( $post_type_object->name );

					$options = array();
					if ( $values = $type->get_meta_values( $column['field'] ) ) {
						foreach ( $values as $value ) {
							$field_value = $value[0];

							// string longer then 30 characters will not be sorted.
							if ( strlen( $field_value ) > 30 )
								continue;

							$options[$field_value] = $field_value;
						}
					}

					$selected = isset( $_REQUEST[$column_name] ) ? $_REQUEST[$column_name] : '';

					$dropdown_filters .= $this->get_dropdown( $column_name, $column['label'], $options, $selected );

					break;

			endswitch;
		}

		if ( ! $dropdown_filters )
			return false;


		// we use the cpac_custom_dropdowns to prevent unnecessary request handling
		echo "
			<input type='hidden' name='cpac_custom_dropdowns'>
			$dropdown_filters
		";
	}
}
