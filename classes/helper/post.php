<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_Post {

	// todo: this is looks like a default set of arguments; how is this a helper? It's more like a config
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
			'paged'          => 1,
		) );

		if ( ! is_numeric( $args['paged'] ) ) {
			$args['paged'] = 1;
		}

		return get_posts( $args );
	}

	// todo: or get_raw_field
	// todo: comes from get_raw_post_field
	/**
	 * @param string $field Post field
	 * @param int $id Post ID
	 *
	 * @return string|false
	 */
	public function get_post_field_raw( $field, $id ) {
		global $wpdb;

		if ( ! $id || ! is_numeric( $id ) ) {
			return false;
		}

		$sql = "
			SELECT " . $wpdb->escape_by_ref( $field ) . " 
			FROM $wpdb->posts
			WHERE ID = %d 
			LIMIT 1
		";

		return $wpdb->get_var( $wpdb->prepare( $sql, $id ) );
	}

	// todo: comes from somewhere, but get_post_field_raw is goodneough for this? the esc_html seems meager addition
	public function title( $post_id ) {
		return esc_html( self::get_raw_post_field( 'post_title', $post_id ) );
	}

	// todo: rename this (get_post_field_formatted) and don't supply default format (!)
	// todo: rewrite to be more elegant
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
				$author = $this->get_post_field_raw( 'post_author', $id );

				if ( $user = get_userdata( $author ) ) {
					$formatted_post = $user->display_name;
				} else {
					$formatted_post = $author;
				}

				break;
			case 'title' :
				$formatted_post = $this->get_post_field_raw( 'post_title', $id );

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
		return $this->get_post_selection_options( $this->get_posts( $args ), $format );
	}

	// todo: rename this to a more semantic functions
	// todo: rewrite some things like the first check, get all posts instead of n times get_post
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
	public function get_post_selection_options( $post_ids, $format = 'title' ) {
		$processed = array();
		$options = array();

		if ( $post_ids ) {
			foreach ( $post_ids as $post_id ) {
				$post = get_post( $post_id );

				if ( ! isset( $options[ $post->post_type ] ) ) {
					$post_type_object = get_post_type_object( $post->post_type );

					$options[ $post->post_type ] = array(
						'label'   => $post_type_object ? $post_type_object->labels->name : $post->post_type,
						'options' => array(),
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

	// todo: values from what? need a better name like get_meta_values_by_meta_key?
	/**
	 * @param $post WP_Post|int
	 * @param $term_ids array Term ID's
	 * @param $taxonomy string Taxonomy name
	 */
	public function set_terms( $post, $term_ids, $taxonomy ) {
		$post = get_post( $post );

		if ( ! $post || empty( $term_ids ) || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		// Filter list of terms
		if ( empty( $term_ids ) ) {
			$term_ids = array();
		}


	// todo: not sure how $terms_ids got here???
	// todo: values from what? naming maybe slightly better
	/**
	 * @since NEWVERSION
	 */
	public function get_values_by_meta_key( $meta_key, $post_type, $operator = 'DISTINCT meta_value AS value' ) {
		global $wpdb;

		$term_ids = array_unique( (array) $term_ids );

		// maybe create terms?
		$created_term_ids = array();

		foreach ( (array) $term_ids as $index => $term_id ) {
			if ( is_numeric( $term_id ) ) {
				continue;
			}

			if ( $term = get_term_by( 'name', $term_id, $taxonomy ) ) {
				$term_ids[ $index ] = $term->term_id;
			}
			else {
				$created_term = wp_insert_term( $term_id, $taxonomy );
				$created_term_ids[] = $created_term['term_id'];
			}
		}

		// merge
		$term_ids = array_merge( $created_term_ids, $term_ids );

		//to make sure the terms IDs is integers:
		$term_ids = array_map( 'intval', (array) $term_ids );
		$term_ids = array_unique( $term_ids );

		if ( $taxonomy == 'category' && is_object_in_taxonomy( $post->post_type, 'category' ) ) {
			wp_set_post_categories( $post->ID, $term_ids );
		}
		else if ( $taxonomy == 'post_tag' && is_object_in_taxonomy( $post->post_type, 'post_tag' ) ) {
			wp_set_post_tags( $post->ID, $term_ids );
		}
		else {
			wp_set_object_terms( $post->ID, $term_ids, $taxonomy );
		}
	}

	// todo: you expect fields here, but looks like values?
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

	/**
	 * Display terms
	 * Largerly taken from class-wp-post-list-table.php
	 *
	 * @since 1.0
	 *
	 * @param int $id Post ID
	 * @param string $taxonomy Taxonomy name
	 */
	public function get_terms_for_display( $post_id, $taxonomy ) {
		return ac()->helper()->term()->display( get_the_terms( $post_id, $taxonomy ), get_post_type( $post_id ) );
	}

	/**
	 * Get terms selection options
	 *
	 * @param string $taxonomy
	 * @param string $default_label
	 *
	 * @return array
	 */
	public function get_term_selection_options( $taxonomy, $default_label = '' ) {
		$options = array();

		if ( $default_label ) {
			$options[''] = $default_label;
		}

		$terms = get_terms( $taxonomy, array(
			'hide_empty' => 0,
		) );

		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = htmlspecialchars_decode( $term->name );
			}
		}

		return $options;
	}
}