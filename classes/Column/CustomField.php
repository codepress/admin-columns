<?php

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all list screens supporting WordPress' default way of handling meta data.
 *
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 *
 * @since 1.0
 */
abstract class AC_Column_CustomField extends AC_Column implements AC_Column_CustomFieldInterface {

	public function __construct() {
		$this->set_type( 'column-meta' );
		$this->set_label( __( 'Custom Field', 'codepress-admin-columns' ) );
		$this->set_group( __( 'Custom Field', 'codepress-admin-columns' ) );
	}

	protected function get_cache_key() {
		return $this->get_meta_type();
	}

	public function get_field_key() {
		// TODO
		$field = $this->get_option( 'field' );

		return substr( $field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden', '', $field ) : $field;
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field_type() {
		// TODO
		return $this->get_option( 'field_type' );
	}

	/**
	 * @since NEWVERSION
	 * @return bool|mixed
	 */
	public function get_field_label() {
		$field_labels = $this->get_field_labels();

		return isset( $field_labels[ $this->get_field_type() ] ) ? $field_labels[ $this->get_field_type() ] : false;
	}

	/**
	 * @since 3.2.1
	 */
	public function is_field_type( $type ) {
		return $type === $this->get_field_type();
	}

	/**
	 * @since 3.2.1
	 */
	public function is_field( $field ) {
		return $field === $this->get_field();
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field() {
		return $this->get_field_key();
	}

	/**
	 * @return array
	 */
	public function get_meta() {
		return array();
	}

	/**
	 * Retrieve metadata object type (e.g., comment, post, or user)
	 *
	 * @since NEWVERSION
	 * @return bool
	 */
	public function get_meta_type() {
		return $this->get_list_screen()->get_meta_type();
	}

	/**
	 * Get Custom FieldType Options - Value method
	 *
	 * @since 1.0
	 *
	 * @return array Custom Field types.
	 */
	public function get_field_labels() {

		$custom_field_types = array(
			'checkmark'   => __( 'Checkmark (true/false)', 'codepress-admin-columns' ),
			'color'       => __( 'Color', 'codepress-admin-columns' ),
			'count'       => __( 'Counter', 'codepress-admin-columns' ),
			'date'        => __( 'Date', 'codepress-admin-columns' ),
			'excerpt'     => __( 'Excerpt' ),
			'image'       => __( 'Image', 'codepress-admin-columns' ),
			'library_id'  => __( 'Media Library', 'codepress-admin-columns' ),
			'link'        => __( 'Url', 'codepress-admin-columns' ),
			'array'       => __( 'Multiple Values', 'codepress-admin-columns' ),
			'numeric'     => __( 'Numeric', 'codepress-admin-columns' ),
			'title_by_id' => __( 'Post Title (Post ID\'s)', 'codepress-admin-columns' ),
			'user_by_id'  => __( 'Username (User ID\'s)', 'codepress-admin-columns' ),
			'term_by_id'  => __( 'Term Name (Term ID\'s)', 'codepress-admin-columns' ),
			'has_content' => __( 'Has Content', 'codepress-admin-columns' ),
		);

		asort( $custom_field_types );

		// Default option comes first
		$custom_field_types = array_merge( array( '' => __( 'Default', 'codepress-admin-columns' ) ), $custom_field_types );

		/**
		 * Filter the available custom field types for the meta (custom field) field
		 *
		 * @since 2.0
		 *
		 * @param array $custom_field_types Available custom field types ([type] => [label])
		 */
		$custom_field_types = apply_filters( 'cac/column/meta/types', $custom_field_types );

		return $custom_field_types;
	}

	/**
	 * @see AC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $id, $single = true ) {
		$raw_value = '';

		if ( $field_key = $this->get_field_key() ) {
			$raw_value = get_metadata( $this->get_meta_type(), $id, $field_key, $single );
		}

		return apply_filters( 'cac/column/meta/raw_value', $raw_value, $id, $field_key, $this );
	}

	/**
	 * @since 2.5.6
	 */
	public function get_username_by_id( $user_id ) {
		$username = false;
		if ( $user_id && is_numeric( $user_id ) && ( $userdata = get_userdata( $user_id ) ) ) {
			$username = $userdata->display_name;
		}

		return $username;
	}

	/**
	 * @since 2.4.7
	 */
	public function get_meta_keys() {
		if ( $cache = wp_cache_get( $this->get_cache_key(), 'cac_columns' ) ) {
			$keys = $cache;
		}
		else {
			$keys = $this->get_meta();

			wp_cache_add( $this->get_cache_key(), $keys, 'cac_columns', 12 ); // 12 sec.
		}

		if ( is_wp_error( $keys ) || empty( $keys ) ) {
			$keys = false;
		}
		else {
			foreach ( $keys as $k => $key ) {

				// give hidden keys a prefix
				$keys[ $k ] = "_" == substr( $key[0], 0, 1 ) ? 'cpachidden' . $key[0] : $key[0];
			}
		}

		// TODO: deprecate filters

		/**
		 * Filter the available custom field meta keys
		 * If showing hidden fields is enabled, they are prefixed with "cpachidden" in the list
		 *
		 * @since 2.0
		 *
		 * @param array $keys Available custom field keys
		 * @param AC_ListScreen $list_screen List screen class instance
		 */
		//$keys = apply_filters( 'cac/storage_model/meta_keys', $keys, $this->get_list_screen() );

		/**
		 * Filter the available custom field meta keys for this list screen type
		 *
		 * @since 2.0
		 * @see Filter cac/list_screen/meta_keys
		 */
		//$keys = apply_filters( "cac/storage_model/meta_keys/storage_key=" . $this->get_list_screen_key(), $keys, $this->get_list_screen() );

		// @since NEWVERSION
		$keys = apply_filters( 'ac/column/custom_fields', $keys, $this );

		return $keys;
	}

	protected function get_grouped_field_options() {
		$grouped_options = array();

		if ( $keys = $this->get_meta_keys() ) {
			$grouped_options = array(
				'hidden' => array(
					'title'   => __( 'Hidden Custom Fields', 'codepress-admin-columns' ),
					'options' => '',
				),
				'public' => array(
					'title'   => __( 'Custom Fields', 'codepress-admin-columns' ),
					'options' => '',
				),
			);

			foreach ( $keys as $field ) {
				if ( substr( $field, 0, 10 ) == "cpachidden" ) {
					$grouped_options['hidden']['options'][ $field ] = substr( $field, 10 );
				}
				else {
					$grouped_options['public']['options'][ $field ] = $field;
				}
			}

			krsort( $grouped_options ); // public first
		}

		return $grouped_options;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_CustomField( $this ) );
		$this->add_setting( new AC_Settings_Setting_BeforeAfter( $this ) );
	}

	/**
	 * @since 1.0
	 *
	 * @param string $meta
	 *
	 * @return int[] Array with integers
	 */
	public function get_ids_from_meta( $meta ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->string->string_to_array_integers()' );

		return ac_helper()->string->string_to_array_integers( $meta );
	}

	/**
	 * Get meta by ID
	 *
	 * @since 1.0
	 *
	 * @param int $id ID
	 *
	 * @deprecated
	 * @return string Meta Value
	 */
	public function get_meta_by_id( $id ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->array->implode_recursive()' );

		return ac_helper()->array->implode_recursive( ', ', $this->get_raw_value( $id ) );
	}

}