<?php

class AC_Helper_Post {

	/**
	 * Search posts
	 *
	 * @since NEWVERSION
	 *
	 * @param $searchterm string
	 * @param $args array
	 *
	 * @return array Post ID's
	 */
	public function get_posts( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'posts_per_page' => 60,
			'post_type'      => 'any',
			'post_status'    => 'any',
			'orderby'        => 'title',
			'order'          => 'ASC',
			's'              => '',
			'fields'         => 'ids',
			'paged'          => 1
		) );

		if ( ! is_numeric( $args['paged'] ) ) {
			$args['paged'] = 1;
		}

		return get_posts( $args );
	}

	/**
	 * @param string $field Field
	 * @param int $id Post ID
	 *
	 * @return string|false
	 */
	public function get_raw_post_field( $field, $id ) {
		global $wpdb;

		return $id && is_numeric( $id ) ? $wpdb->get_var( $wpdb->prepare( "SELECT " . $wpdb->_real_escape( $field ) . " FROM {$wpdb->posts} WHERE ID = %d LIMIT 1", $id ) ) : false;
	}

	/**
	 * @param int $post_id
	 * @param string $format
	 *
	 * @return false|string
	 */
	public function format_post( $id, $format = 'title' ) {
		$formatted_post = $id;

		switch ( $format ) {
			case 'user' :
				$author = $this->get_raw_post_field( 'post_author', $id );
				if ( $user = get_userdata( $author ) ) {
					$formatted_post = $user->display_name;
				}
				else {
					$formatted_post = $author;
				}
				break;
			case 'title' :
				$formatted_post = $this->get_raw_post_field( 'post_title', $id );
				break;
		}

		return $formatted_post;
	}

	/**
	 * @param array $args
	 * @param $format string
	 *
	 * @return array
	 */
	public function get_posts_for_selection( $args = array(), $format = 'title' ) {
		return $this->format_options( $this->get_posts( $args ), $format );
	}

	/**
	 * Format options for posts selection
	 *
	 * Results are formatted as an array of post types, the key being the post type name, the value
	 * being an array with two keys: label (the post type label) and options, an array of options (posts)
	 * for this post type, with the post IDs as keys and the post titles as values
	 *
	 * @since 1.0
	 * @uses WP_Query
	 *
	 * @param array $query_args Additional query arguments for WP_Query
	 *
	 * @return array List of options, grouped by posttype
	 */
	public function format_options( $post_ids, $format = 'title' ) {
		$processed = array();
		$options = array();

		if ( $post_ids ) {
			foreach ( $post_ids as $post_id ) {
				$post = get_post( $post_id );

				if ( ! isset( $options[ $post->post_type ] ) ) {
					$post_type_object = get_post_type_object( $post->post_type );

					$options[ $post->post_type ] = array(
						'label'   => $post_type_object ? $post_type_object->labels->name : $post->post_type,
						'options' => array()
					);
				}

				$label = $this->format_post( $post->ID, $format );

				// Add ID to duplicates
				if ( isset( $processed[ $post->post_type ] ) && in_array( $label, $processed[ $post->post_type ] ) ) {
					$label .= ' - #' . $post->ID;
				}

				$options[ $post->post_type ]['options'][ $post->ID ] = $label;

				$processed[ $post->post_type ][] = $label;
			}
		}

		return $options;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_single_values_by_meta_key( $meta_key, $post_type ) {
		$values = array();
		if ( $results = self::get_values_by_meta_key( $meta_key, $post_type ) ) {
			foreach ( $results as $k => $data ) {
				$values[ $data->value ] = $data->value;
			}
		}

		return $values;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_values_by_meta_key( $meta_key, $post_type, $operator = 'DISTINCT meta_value AS value' ) {
		global $wpdb;

		$sql = $wpdb->prepare( "
			SELECT {$operator}
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
			WHERE p.post_type = %s
			AND pm.meta_key = %s
			AND pm.meta_value != ''
			ORDER BY 1
		", $post_type, $meta_key );

		$values = $wpdb->get_results( $sql );

		return $values && ! is_wp_error( $values ) ? $values : array();
	}

	/**
	 * Get values by post field
	 *
	 * @since 1.0
	 */
	public function get_post_fields( $post_field, $post_type ) {
		global $wpdb;

		$post_field = sanitize_key( $post_field );

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
	 * Get terms by taxonomy and post type
	 *
	 * @since 3.8
	 */
	public function get_terms_by_post_type( $taxonomies, $post_types ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT t.term_id AS term_id, t.slug AS slug, t.name AS name, tt.taxonomy AS taxonomy, tt.parent AS parent
			FROM $wpdb->terms AS t
	        INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
	        INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
	        INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
	        WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
	        GROUP BY t.term_id",
			join( "', '", (array) $post_types ),
			join( "', '", (array) $taxonomies )
		);

		$results = $wpdb->get_results( $query );

		return $results;
	}
}