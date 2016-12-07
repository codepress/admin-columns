<?php

class AC_Settings_Setting_CustomFieldType extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $field_type;

	protected function set_managed_options() {
		$this->managed_options = array( 'field_type' );
	}

	public function get_dependent_settings() {
		$settings = array();

		switch ( $this->get_field_type() ) {
			case 'date' :
				$settings[] = new AC_Settings_Setting_Date( $this->column );
				break;
			case 'image' :
			case 'library_id' :
				$settings[] = new AC_Settings_Setting_Image( $this->column );
				break;
			case 'excerpt' :
				$settings[] = new AC_Settings_Setting_CharacterLimit( $this->column );
				break;
			case 'title_by_id' :
				$settings[] = new AC_Settings_Setting_Post( $this->column );
				break;
			case 'user_by_id' :
				$settings[] = new AC_Settings_Setting_User( $this->column );
				break;
		}

		return $settings;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_field_type_options() );

		$tooltip = __( 'This will determine how the value will be displayed.', 'codepress-admin-columns' );

		if ( $this->get_field_type() ) {
			$tooltip .= '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->get_field_type() . '</em>';
		}

		$view = new AC_View( array(
			'label'   => __( 'Field Type', 'codepress-admin-columns' ),
			'tooltip' => $tooltip,
			'setting' => $select,
		) );

		return $view;
	}

	/**
	 * Get possible field types
	 *
	 * @return array
	 */
	private function get_field_type_options() {
		$field_types = array(
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
			//'term_by_id'  => __( 'Term Name (Term ID\'s)', 'codepress-admin-columns' ),
			'has_content' => __( 'Has Content', 'codepress-admin-columns' ),
		);

		asort( $field_types );

		// Default option comes first
		$field_types = array_merge( array( '' => __( 'Default', 'codepress-admin-columns' ) ), $field_types );

		/**
		 * Filter the available custom field types for the meta (custom field) field
		 *
		 * @since 2.0
		 *
		 * @param array $field_types Available custom field types ([type] => [label])
		 */
		$field_types = apply_filters( 'cac/column/meta/types', $field_types );

		return $field_types;
	}

	/**
	 * @param array|string $id
	 *
	 * @return string|bool
	 */
	public function format( $object_id ) {

		if ( ! $this->column instanceof AC_Column_CustomFieldInterface ) {
			return $object_id;
		}

		$value = $object_id;

		$meta_data = $this->column->get_raw_value( $object_id );

		switch ( $this->get_field_type() ) {
			case 'image' :
			case 'library_id' :
				$string = ac_helper()->array->implode_recursive( ', ', $meta_data );
				$value = ac_helper()->string->comma_separated_to_array( $string );
				break;

			case 'title_by_id' :
			case 'user_by_id' :
				$string = ac_helper()->array->implode_recursive( ', ', $meta_data );
				$value = ac_helper()->string->string_to_array_integers( $string );
				break;

			case "checkmark" :
				$is_true = ( ! empty( $meta_data ) && 'false' !== $meta_data && '0' !== $meta_data );
				$value = ac_helper()->icon->yes_or_no( $is_true );
				break;

			case "color" :
				$value = $meta_data && is_scalar( $meta_data ) ? ac_helper()->string->get_color_block( $meta_data ) : ac_helper()->string->get_empty_char();
				break;

			case "count" :
				$meta_data = get_metadata( $this->column->get_list_screen()->get_meta_type(), $object_id, $this->column->get_field_key(), false );
				$value = $meta_data ? count( $meta_data ) : ac_helper()->string->get_empty_char();
				break;

			case "has_content" :
				$value = ac_helper()->icon->yes_or_no( $meta_data, $meta_data );
				break;
		}

		return $value;
	}

	/**
	 * @return string
	 */
	public function get_field_type() {
		return $this->field_type;
	}

	/**
	 * @param string $field_type
	 *
	 * @return $this
	 */
	public function set_field_type( $field_type ) {
		$this->field_type = $field_type;

		return $this;
	}

}