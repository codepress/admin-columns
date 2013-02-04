<?php

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
function cpac_get_vars_post__in( $vars, $sortposts, $sort_flags = SORT_REGULAR ) {
	if ( $vars['order'] == 'asc' ) {
		asort( $unsorted, SORT_REGULAR );
	}
	else {
		arsort( $unsorted, SORT_REGULAR );
	}

	$vars['orderby']	= 'post__in';
	$vars['post__in']	 = array_keys( $unsorted );

	return $vars;
}

/**
 * Sortables
 *
 * @since 2.0.0
 */
class CAC_Addon_Sortable {

	
	
}
class CPAC_Column_Post_Custom_Field_Sortable {

	
	function get_sortable_vars( $vars, $posts = array() ) {
		$unsorted = array();

		foreach ( $posts as $p ) {
			$unsorted[$p->ID] = $p->ID;
			
			if ( ! has_post_thumbnail( $p->ID ) ) {
				$unsorted[$p->ID] = 0;
			}
		}

		return $this->cpac_get_vars_post__in( $vars, $unsorted, SORT_REGULAR );
	}		
}

class CPAC_Column_Post_ID_Sortable extends CPAC_Column_Post_ID {
	
	function __construct( $storage_model ) {
		parent::__construct( $storage_model );
	}
	
	function get_sortable_vars( $vars, $posts = array() ) {
		$vars['orderby'] = 'ID';
	}		
}

