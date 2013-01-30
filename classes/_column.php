<?php

abstract class CPAC_Column {

	protected $label;
	protected $options = array();

	/**
	 *
	 * @param type $key
	 * @return boolean
	 */
	function get_option( $key ) {
		if ( ! isset( $this->options[ $key ] ) )
			return false;

		return $this->options[ $key ];
	}

	/**
	 *
	 * @return type
	 */
	function get_label() {
		return $this->label;
	}

	/**
	 *
	 */
	abstract function get_value( $object_id );
}

interface CPAC_Sortable {
	function get_sorting_vars( $vars, $posts );
}

class CPAC_Column_Post_Featured_Image extends CPAC_Column implements CPAC_Sortable {

	function __construct() {
		$this->label	= __( 'Featured Image', CPAC_TEXTDOMAIN );
		$this->options  = array(
			'is_image'	=> true
		);
	}

	function get_value( $post_id ) {
		$thumbnail_size = apply_filters( 'cpac_thumbnail_size', array( 80,80 ) );

		if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) {
			return get_the_post_thumbnail( $post_id, $thumbnail_size );
		}
	}

	function get_sorting_vars( $vars, $posts = array() ) {
		$unsorted = array();

		foreach ( $posts as $p ) {
			$unsorted[$p->ID] = $p->ID;

			$thumb = get_the_post_thumbnail( $p->ID );
			if ( ! empty( $thumb ) ) {
				$unsorted[$p->ID] = 0;
			}
		}

		return CPAC_Sortable::get_vars_post__in( $vars, $unsorted, SORT_REGULAR );
	}

}