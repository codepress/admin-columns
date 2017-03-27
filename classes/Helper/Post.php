<?php

class AC_Helper_Post {

	/**
	 * @param string $field Field
	 * @param int    $id    Post ID
	 *
	 * @return string|false
	 */
	public function get_raw_field( $field, $id ) {
		global $wpdb;

		if ( ! $id || ! is_numeric( $id ) ) {
			return false;
		}

		$sql = "
			SELECT " . $wpdb->_real_escape( $field ) . "
			FROM $wpdb->posts
			WHERE ID = %d
			LIMIT 1
		";

		return $wpdb->get_var( $wpdb->prepare( $sql, $id ) );
	}

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function post_exists( $id ) {
		return $this->get_raw_field( 'ID', $id ) ? true : false;
	}

	/**
	 * @param int $id Post ID
	 *
	 * @return false|string Post Title
	 */
	public function get_post_title( $id ) {
		return ac_helper()->post->get_raw_field( 'post_title', $id );
	}

	/**
	 * Get values by post field
	 *
	 * @since 1.0
	 */
	public function get_post_values_by_field( $post_field, $post_type ) {
		global $wpdb;

		$post_field = '`' . sanitize_key( $post_field ) . '`';

		$sql = "
			SELECT DISTINCT {$post_field}
			FROM {$wpdb->posts}
			WHERE post_type = %s
			AND {$post_field} <> ''
			ORDER BY 1
		";

		$values = $wpdb->get_col( $wpdb->prepare( $sql, $post_type ) );

		return $values && ! is_wp_error( $values ) ? $values : array();
	}

	/**
	 * @since 1.0
	 *
	 * @param int $post_id Post ID
	 *
	 * @return string Post Excerpt.
	 */
	public function excerpt( $post_id, $words = 400 ) {
		global $post;

		$save_post = $post;
		$post = get_post( $post_id );

		setup_postdata( $post );

		$excerpt = get_the_excerpt();
		$post = $save_post;

		if ( $post ) {
			setup_postdata( $post );
		}

		return ac_helper()->string->trim_words( $excerpt, $words );
	}

	/**
	 * @param string $post_type
	 * @param bool   $plural
	 *
	 * @return bool
	 */
	public function get_post_type_label( $post_type, $plural = false ) {
		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) {
			return false;
		}

		if ( $plural ) {
			return $post_type->labels->name;
		}

		return $post_type->labels->singular_name;
	}

}