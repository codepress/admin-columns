<?php

class AC_Settings_Setting_CustomField extends AC_Settings_SettingAbstract {

	private $field;
	private $field_type;

	protected function set_name() {
		$this->name = 'custom_field';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'field', 'field_type' );
	}

	private function get_grouped_field_options() {
		$grouped_options = array();

		if ( $keys = $this->column->get_meta_keys() ) {
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

	private function get_field_labels() {

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

	public function view() {

		$sections = array();

		$select = $this->create_element( 'select', 'field' )
		               ->set_options( $this->get_grouped_field_options() );

		$field = new AC_Settings_View();
		$field->set( 'label', __( 'Custom Field', 'codepress-admin-columns' ) )
		      ->set( 'setting', $select );

		$sections[] = $field;

		$select = $this->create_element( 'select', 'field_type' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_field_labels() );

		$field_type = new AC_Settings_View();
		$field_type->set( 'label', __( 'Field Type', 'codepress-admin-columns' ) )
		           ->set( 'setting', $select );

		$sections[] = $field_type;

		// Optional fields

		// TODO: doesn't work when switching field type
		switch ( $this->get_field_type() ) {
			case 'date' :
				$sections[] = new AC_Settings_Setting_Date( $this->column );
				break;
			case 'image' :
			case 'library_id' :
				$sections[] = new AC_Settings_Setting_Image( $this->column );
				break;
			case 'excerpt' :
				$sections[] = new AC_Settings_Setting_CharacterLimit( $this->column );
				break;
			case 'title_by_id' :
				$sections[] = new AC_Settings_Setting_Post( $this->column );
				break;
			case 'user_by_id' :
				$sections[] = new AC_Settings_Setting_User( $this->column );
				break;
		}

		$view = $this->get_view();
		$view->set( 'label', __( 'Custom Field', 'codepress-admin-columns' ) )
		     ->set( 'sections', $sections );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_field() {
		return $this->field;
	}

	/**
	 * @param string $field
	 *
	 * @return $this
	 */
	public function set_field( $field ) {
		$this->field = $field;

		return $this;
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