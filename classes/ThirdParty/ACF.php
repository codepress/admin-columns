<?php

class AC_ThirdParty_ACF {

	public function __construct() {
		add_filter( 'cac/post_types', array( $this, 'remove_acf_from_cpac_post_types' ) );
		add_filter( 'cac/grouped_columns', array( $this, 'place_acf_on_top_of_group_list' ) );
	}

	/**
	 * Fix which remove the Advanced Custom Fields Type (acf) from the admin columns settings page
	 *
	 * @since 2.0
	 *
	 * @return array Post Types
	 */
	function remove_acf_from_cpac_post_types( $post_types ) {
		if ( class_exists( 'Acf', false ) ) {
			if ( isset( $post_types['acf'] ) ) {
				unset( $post_types['acf'] );
			}
			if ( isset( $post_types['acf-field-group'] ) ) {
				unset( $post_types['acf-field-group'] );
			}
		}

		return $post_types;
	}

	/**
	 * place ACF on top of the grouped list
	 */
	function place_acf_on_top_of_group_list( $grouped_columns ) {
		$label = __( 'Advanced Custom Fields', 'acf' );

		if ( isset( $grouped_columns[ $label ] ) ) {
			$group[ $label ] = $grouped_columns[ $label ];
			unset( $grouped_columns[ $label ] );
			$grouped_columns = $group + $grouped_columns;
		}

		return $grouped_columns;
	}

}