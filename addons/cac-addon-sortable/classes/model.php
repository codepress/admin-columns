<?php

/**
 * Addon class
 *
 * @since 0.1
 *
 */
abstract class CAC_Sortable_Model {	
	
	protected $storage_model;
	
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct( $storage_model ) {
	
		$this->storage_model = $storage_model;		
	}	
	
	/**
	 * Get orderby type
	 *
	 * @since 1.1.0
	 *
	 * @param string $orderby
	 * @param string $type
	 * @return array Column
	 */
	protected function get_orderby_type( $orderby ) {
		
		$column = false;

		if ( $columns = $this->storage_model->get_columns() ) {
			foreach ( $columns as $_column ) {
				if ( $orderby == CPAC_Utility::sanitize_string( $_column->options->label ) ) {
				
					$column = $_column;
				}
			}
		}
		
		return apply_filters( 'cpac_get_orderby_type', $column, $orderby, $this->storage_model->key );
	}
	
	/**
	 * Set post__in for use in WP_Query
	 *
	 * This will order the ID's asc or desc and set the appropriate filters.
	 *
	 * @since 1.2.1
	 *
	 * @param array &$vars
	 * @param array $sortposts
	 * @param const $sort_flags
	 * @return array Posts Variables
	 */
	protected function get_vars_post__in( $vars, $unsorted, $sort_flag = SORT_REGULAR ) {
		
		if ( $vars['order'] == 'asc' )
			asort( $unsorted, $sort_flag );		
		else
			arsort( $unsorted, $sort_flag );		

		$vars['orderby']	= 'post__in';
		$vars['post__in']	= array_keys( $unsorted );

		return $vars;
	}
			
	/**
	 * Set sorting preference
	 *
	 * after sorting we will save this sorting preference to the column item
	 * we set the default_order to either asc, desc or empty.
	 * only ONE column item PER type can have a default_order
	 *
	 * @since 1.4.6.5
	 *
	 * @param string $type
	 * @param string $orderby
	 * @param string $order asc|desc
	 */
	protected function set_sorting_preference( $type, $orderby = '', $order = 'asc' ) {
		
		if ( !$orderby )
			return false;

		$options = get_user_meta( $this->current_user_id, 'cpac_sorting_preference', true );

		$options[ $type ] = array(
			'orderby'	=> $orderby,
			'order'		=> $order
		);

		update_user_meta( $this->current_user_id, 'cpac_sorting_preference', $options );
	}
	
	/**
	 * Get sorting preference
	 *
	 * The default sorting of the column is saved to it's property default_order.
	 * Returns the orderby and order value of that column.
	 *
	 * @since 1.4.6.5
	 *
	 * @param string $type
	 * @return array Sorting Preferences for this type
	 */
	protected function get_sorting_preference( $type )
	{
		$options = get_user_meta( $this->current_user_id, 'cpac_sorting_preference', true );

		if ( empty($options[$type]) )
			return false;

		return $options[$type];
	}
	
	/**
	 * Apply sorting preference
	 *
	 * @since 1.4.6.5
	 *
	 * @todo active state in header
	 * @param array &$vars
	 * @param string $type
	 */
	protected function apply_sorting_preference( &$vars, $type ) {
		
		// user has not sorted
		if ( empty( $vars['orderby'] ) ) {
						
			$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

			// did the user sorted this column some other time?
			if ( ! empty( $options[ $type ] ) ) {
				$vars['orderby'] = $options[ $type ]['orderby'];
				$vars['order'] 	 = $options[ $type ]['order'];
			}
		}

		// save the order preference
		if ( ! empty( $vars['orderby'] ) ) {

			$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

			$options[ $type ] = array(
				'orderby'	=> $vars['orderby'],
				'order'		=> $vars['order']
			);

			update_user_meta( get_current_user_id(), 'cpac_sorting_preference', $options );			
		}
	}
	
	/**
	 * Prepare the value for being by sorting
	 *
	 * Removes tags and only get the first 20 chars and force lowercase.
	 *
	 * @since 1.3.0
	 *
	 * @param string $string
	 * @return string String
	 */
	protected function prepare_sort_string_value( $string ) {

		return strtolower( substr( CPAC_Utility::strip_trim( $string ), 0, 20 ) );
	}	
}