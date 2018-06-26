<?php

namespace AC\Helper;

class Post {

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
	 * @param int|\WP_Post $post
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

	/**
	 * @param \WP_Post $post Post
	 *
	 * @return false|string Dash icon with tooltip
	 */
	public function get_status_icon( $post ) {
		$icon = false;

		switch ( $post->post_status ) {
			case 'private' :
				$icon = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'hidden', 'class' => 'gray' ) ), __( 'Private' ) );
				break;
			case 'publish' :
				$icon = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'yes', 'class' => 'blue large' ) ), __( 'Published' ) );
				break;
			case 'draft' :
				$icon = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'edit', 'class' => 'green' ) ), __( 'Draft' ) );
				break;
			case 'pending' :
				$icon = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'backup', 'class' => 'orange' ) ), __( 'Pending for review' ) );
				break;
			case 'future' :
				$icon = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'clock' ) ), __( 'Scheduled' ) . ': <em>' . ac_helper()->date->date( $post->post_date, 'wp_date_time' ) . '</em>' );

				// Missed schedule
				if ( ( time() - mysql2date( 'G', $post->post_date_gmt ) ) > 0 ) {
					$icon .= ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'flag', 'class' => 'gray' ) ), __( 'Missed schedule' ) );
				}
				break;
		}

		return $icon;
	}

}