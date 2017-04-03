<?php

class AC_Helper_Post {

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function exists( $id ) {
		return $this->get_raw_field( 'ID', $id ) ? true : false;
	}

	/**
	 * @param int $id Post ID
	 *
	 * @return false|string Post Title
	 */
	public function get_raw_post_title( $id ) {
		return ac_helper()->post->get_raw_field( 'post_title', $id );
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
	 * Get Post Title or Media Filename
	 *
	 * @param int|WP_Post $post
	 *
	 * @return bool|string
	 */
	public function get_title( $post ) {
		$post = get_post( $post );

		if ( ! $post ) {
			return false;
		}

		$title = $post->post_title;

		if ( 'attachment' == $post->post_type ) {
			$title = ac_helper()->image->get_file_name( $post->ID );
		}

		return $title;
	}

}