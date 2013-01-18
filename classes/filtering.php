<?php
/**
 * class CPAC_Filtering_Columns
 *
 * @since 2.0
 */
class CPAC_Filtering_Columns {

	function __construct() {
		add_action( 'admin_init', array( $this, 'register_filtering_columns' ) );
	}

	/**
	 * Register filtering columns
	 *
	 * @since 1.4.2
	 */
	function register_filtering_columns() {

		add_action( 'restrict_manage_posts', array( $this, 'callback_restrict_posts' ) );
	}

	/**
	 * Add taxonomy filters to posts
	 *
	 * @since 1.4.2
	 */
	function callback_restrict_posts() {
		global $post_type_object;

		if ( ! isset( $post_type_object->name ) )
			return false;

		// get stored columns
		if( ! $columns = CPAC_Utility::get_stored_columns( $post_type_object->name ) )
			return false;

		$result = '';

		foreach ( $columns as $column_name => $column ){

			// check if taxonomy column is active
			if ( isset( $column['state'] ) && 'on' !== $column['state'] )
				continue;

			// check if taxonomy column has filtering enabled
			if ( isset( $column['filtering'] ) && 'on' !== $column['filtering'] )
				continue;

			$column_name_type = CPAC_Utility::get_column_name_type( $column_name );

			$result = '';

			switch( $column_name_type ) :

				case 'column-taxonomy' :

					if ( $taxonomy_slug = CPAC_Utility::get_taxonomy_by_column_name( $column_name ) ) {

						$taxonomy	= get_taxonomy( $taxonomy_slug );
						$terms		= $this->apply_dropdown_markup( $this->indent( get_terms( $taxonomy_slug ), 0, 'parent', 'term_id' ) );

						$select = "<option value=''>".__( 'Show all ', CPAC_TEXTDOMAIN )."{$taxonomy->label}</option>";
						if ( ! empty( $terms ) ) {
							foreach( $terms as $term_slug => $term ) {

								//$selected = isset( $_GET[$tax] ) && $term_slug == $_GET[$tax] ? " selected='selected'" : '';
								$selected = '';
								$select .= "<option value='{$term_slug}'{$selected}>{$term}</option>";
							}
							$result = "<select class='postform' name='{$taxonomy_slug}'>{$select}</select>";
						}
					}

					break;

				case 'column-meta' :
					break;

			endswitch;


		}

		echo $result;




		if ( $taxonomies = get_object_taxonomies( $post_type_object->name ) ) {
			foreach ( $taxonomies as $tax ) {

				$tax_obj = get_taxonomy( $tax );

				// ignore core taxonomies
				if ( in_array( $tax, array( 'post_tag', 'category', 'post_format' ) ) ) {
					continue;
				}

				// check if column exists
				if ( isset( $columns['column-taxonomy-'.$tax] ) ) {
					$column_name = 'column-taxonomy-'.$tax;
				}
				elseif ( isset( $columns['taxonomy-'.$tax] ) ) {
					$column_name = 'taxonomy-'.$tax;
				}
				else {
					continue;
				}

				// check if taxonomy column is active
				if ( isset( $columns[$column_name]['state'] ) && 'on' !== $columns[$column_name]['state'] ){
					continue;
				}

				// check if taxonomy column has filtering enabled
				if ( isset( $columns[$column_name]['filtering'] ) && 'on' !== $columns[$column_name]['filtering'] ){
					continue;
				}

				$terms = $this->apply_dropdown_markup( $this->indent( get_terms( $tax ), 0, 'parent', 'term_id' ) );

				$select = "<option value=''>".__( 'Show all ', CPAC_TEXTDOMAIN )."{$tax_obj->label}</option>";
				if ( ! empty( $terms ) ) {
					foreach( $terms as $term_slug => $term ) {

						$selected = isset( $_GET[$tax] ) && $term_slug == $_GET[$tax] ? " selected='selected'" : '';
						$select .= "<option value='{$term_slug}'{$selected}>{$term}</option>";
					}
					echo "<select class='postform' name='{$tax}'>{$select}</select>";
				}

			}
		}
	}

	/**
	 * Applies dropdown markup for taxonomy dropdown
	 *
	 * @since 1.4.2
	 *
	 * @param array $array
	 * @param int $level
	 * @param array $ouput
	 * @return array Output
	 */
	private function apply_dropdown_markup( $array, $level = 0, $output = array() ) {
        foreach( $array as $v ) {

            $prefix = '';
            for( $i=0; $i<$level; $i++ ) {
                $prefix .= '&nbsp;&nbsp;';
            }

            $output[$v->slug] = $prefix . $v->name;

            if ( ! empty( $v->children ) ) {
                $output = $this->apply_dropdown_markup( $v->children, ( $level + 1 ), $output );
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
}
