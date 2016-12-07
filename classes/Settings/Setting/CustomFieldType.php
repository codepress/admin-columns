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
			'label'    => __( 'Field Type', 'codepress-admin-columns' ),
			'tooltip'  => $tooltip,
			'setting'  => $select,
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
			'term_by_id'  => __( 'Term Name (Term ID\'s)', 'codepress-admin-columns' ),
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
	public function format( $id ) {

		$value = false;

		return $value;

		// Create an array
		$raw_value = $this->column->get_raw_value( $id );
		$raw_string = ac_helper()->array->implode_recursive( ', ', $raw_value );

		switch ( $this->get_field_type() ) {
			case 'image' :
			case 'library_id' :



				// todo test images, was incomplete when I started it
				$value = ac_helper()->string->comma_separated_to_array( $raw_value );
				break;

			case 'excerpt' :
			case 'date' :
			case 'link' :

				$this->get_dependent_settings();

				$value = $this->get_sub_setting()->format( $raw_value );

				break;
			case 'title_by_id' :
				$titles = array();

				if ( $ids = ac_helper()->string->string_to_array_integers( $raw_string ) ) {
					foreach ( (array) $ids as $id ) {
						$titles[] = $this->get_sub_setting()->format( $id );
					}
				}

				$value = implode( ac_helper()->html->divider(), $titles );

				break;
			case "user_by_id" :
				$names = array();

				if ( $ids = ac_helper()->string->string_to_array_integers( $raw_string ) ) {
					foreach ( (array) $ids as $id ) {
						$names[] = $this->get_sub_setting()->format( $id );
					}
				}

				$value = implode( ac_helper()->html->divider(), $names );

				break;
			case "term_by_id" :
				if ( is_array( $raw_value ) && isset( $raw_value['term_id'] ) && isset( $raw_value['taxonomy'] ) ) {
					$value = ac_helper()->taxonomy->display( (array) get_term_by( 'id', $raw_value['term_id'], $raw_value['taxonomy'] ) );
				}

				break;
			case "checkmark" :
				$is_true = ( ! empty( $raw_value ) && 'false' !== $raw_value && '0' !== $raw_value );

				$value = ac_helper()->icon->yes_or_no( $is_true );

				break;
			case "color" :
				$value = $raw_value && is_scalar( $raw_value ) ? ac_helper()->string->get_color_block( $raw_value ) : ac_helper()->string->get_empty_char();

				break;
			case "count" :
				$raw_value = $this->column->get_raw_value( $id, false );
				$value = $raw_value ? count( $raw_value ) : ac_helper()->string->get_empty_char();

				break;
			case "has_content" :
				$value = '<span class="cpac-tip" data-tip="' . esc_attr( $raw_value ) . '">' . ac_helper()->icon->yes_or_no( $raw_value ) . '</span>';
				break;

			default :
				$value = $raw_string;
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