<?php

namespace AC\Settings\Column;

use AC;
use AC\Collection;
use AC\Settings;
use AC\View;

class CustomFieldType extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $field_type;

	protected function define_options() {
		return array( 'field_type' );
	}

	public function get_dependent_settings() {
		$settings = array();

		switch ( $this->get_field_type() ) {

			case 'date' :
				$settings[] = new Date( $this->column );

				break;
			case 'image' :
			case 'library_id' :
				$settings[] = new Image( $this->column );

				break;
			case 'excerpt' :
				$settings[] = new StringLimit( $this->column );

				break;
			case 'link' :
				$settings[] = new LinkLabel( $this->column );

				break;
		}

		return $settings;
	}

	public function create_view() {
		$select = $this->create_element( 'select' );

		$select->set_attribute( 'data-refresh', 'column' )
		       ->set_options( $this->get_grouped_options() )
		       ->set_description( $this->get_description() );

		$tooltip = __( 'This will determine how the value will be displayed.', 'codepress-admin-columns' );

		if ( null !== $this->get_field_type() ) {
			$tooltip .= '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->get_field_type() . '</em>';
		}

		$view = new View( array(
			'label'   => __( 'Field Type', 'codepress-admin-columns' ),
			'tooltip' => $tooltip,
			'setting' => $select,
		) );

		return $view;
	}

	private function get_description_object_ids( $input ) {
		$description = sprintf( __( "Uses one or more %s IDs to display information about it.", 'codepress-admin-columns' ), '<em>' . $input . '</em>' );
		$description .= ' ' . __( "Multiple IDs should be separated by commas.", 'codepress-admin-columns' );

		return $description;
	}

	public function get_description() {
		$description = false;

		switch ( $this->get_field_type() ) {
			case 'title_by_id' :
				$description = $this->get_description_object_ids( __( "Post Type", 'codepress-admin-columns' ) );

				break;
			case 'user_by_id' :
				$description = $this->get_description_object_ids( __( "User", 'codepress-admin-columns' ) );

				break;
		}

		return $description;
	}

	/**
	 * Get possible field types
	 *
	 * @return array
	 */
	protected function get_field_type_options() {
		$grouped_types = array(
			'basic'      => array(
				'color'   => __( 'Color', 'codepress-admin-columns' ),
				'date'    => __( 'Date', 'codepress-admin-columns' ),
				'excerpt' => __( 'Excerpt', 'codepress-admin-columns' ),
				'image'   => __( 'Image', 'codepress-admin-columns' ),
				'link'    => __( 'URL', 'codepress-admin-columns' ),
				'numeric' => __( 'Number', 'codepress-admin-columns' ),
			),
			'choice'     => array(
				'has_content' => __( 'Has Content', 'codepress-admin-columns' ),
				'checkmark'   => __( 'True / False', 'codepress-admin-columns' ),
			),
			'relational' => array(
				'library_id'  => __( 'Media', 'codepress-admin-columns' ),
				'title_by_id' => __( 'Post', 'codepress-admin-columns' ),
				'user_by_id'  => __( 'User', 'codepress-admin-columns' ),
			),
			'multiple'   => array(
				'count' => __( 'Number of Fields', 'codepress-admin-columns' ),
				'array' => __( 'Multiple Values', 'codepress-admin-columns' ),
			),
		);

		/**
		 * Filter the available custom field types for the meta (custom field) field
		 *
		 * @since 3.0
		 *
		 * @param array $field_types Available custom field types ([type] => [label])
		 */
		$grouped_types['custom'] = apply_filters( 'ac/column/custom_field/field_types', array() );

		foreach ( $grouped_types as $k => $fields ) {
			natcasesort( $grouped_types[ $k ] );
		}

		return $grouped_types;
	}

	/**
	 * @return array
	 */
	private function get_grouped_options() {
		$field_types = $this->get_field_type_options();

		foreach ( $field_types as $fields ) {
			asort( $fields );
		}

		$groups = array(
			'basic'      => __( 'Basic', 'codepress-admin-columns' ),
			'relational' => __( 'Relational', 'codepress-admin-columns' ),
			'choice'     => __( 'Choice', 'codepress-admin-columns' ),
			'multiple'   => __( 'Multiple', 'codepress-admin-columns' ),
			'custom'     => __( 'Custom', 'codepress-admin-columns' ),
		);

		$grouped_options = array();
		foreach ( $field_types as $group => $fields ) {

			if ( ! $fields ) {
				continue;
			}

			$grouped_options[ $group ]['title'] = $groups[ $group ];
			$grouped_options[ $group ]['options'] = $fields;
		}

		// Default option comes first
		$grouped_options = array_merge( array( '' => __( 'Default', 'codepress-admin-columns' ) ), $grouped_options );

		return $grouped_options;
	}

	/**
	 * @param string|array $string
	 *
	 * @return array
	 */
	private function get_values_from_array_or_string( $string ) {
		$string = ac_helper()->array->implode_recursive( ',', $string );

		return ac_helper()->string->comma_separated_to_array( $string );
	}

	/**
	 * @param string|array $string
	 *
	 * @return array
	 */
	private function get_ids_from_array_or_string( $string ) {
		$string = ac_helper()->array->implode_recursive( ',', $string );

		return ac_helper()->string->string_to_array_integers( $string );
	}

	public function format( $value, $original_value ) {

		switch ( $this->get_field_type() ) {

			case 'date' :
				if ( $timestamp = ac_helper()->date->strtotime( $value ) ) {
					$value = date( 'c', $timestamp );
				}

				break;

			case "title_by_id" :
				$values = array();
				foreach ( $this->get_ids_from_array_or_string( $value ) as $id ) {
					$post = get_post( $id );
					$values[] = ac_helper()->html->link( get_edit_post_link( $post ), $post->post_title );
				}

				$value = implode( ac_helper()->html->divider(), $values );
				break;

			case "user_by_id" :
				$values = array();
				foreach ( $this->get_ids_from_array_or_string( $value ) as $id ) {
					$user = get_userdata( $id );
					$values[] = ac_helper()->html->link( get_edit_user_link( $id ), ac_helper()->user->get_display_name( $user ) );
				}

				$value = implode( ac_helper()->html->divider(), $values );

				break;
			case 'image':
				$value = new Collection( $this->get_values_from_array_or_string( $value ) );

				break;
			case 'library_id' :
				$value = new Collection( $this->get_ids_from_array_or_string( $value ) );

				break;
			case "checkmark" :
				$is_true = ! empty( $value ) && 'false' !== $value && '0' !== $value;

				if ( $is_true ) {
					$value = ac_helper()->icon->dashicon( array( 'icon' => 'yes', 'class' => 'green' ) );
				} else {
					$value = ac_helper()->icon->dashicon( array( 'icon' => 'no-alt', 'class' => 'red' ) );
				}

				break;
			case "color" :

				if ( $value && is_scalar( $value ) ) {
					$value = ac_helper()->string->get_color_block( $value );
				} else {
					$value = false;
				}

				break;
			case "count" :

				if ( $this->column instanceof AC\Column\Meta ) {
					$value = $this->column->get_meta_value( $original_value, $this->column->get_meta_key(), false );

					if ( $value ) {
						if ( 1 === count( $value ) && is_array( $value[0] ) ) {

							// Value contains a single serialized array with multiple values
							$value = count( $value[0] );
						} else {

							// Count multiple usage of meta keys
							$value = count( $value );
						}
					} else {
						$value = false;
					}
				}

				break;
			case "has_content" :
				$value = ac_helper()->icon->yes_or_no( $value, $value );

				break;
			default :
				$value = ac_helper()->array->implode_recursive( __( ', ' ), $value );
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
	 * @return bool
	 */
	public function set_field_type( $field_type ) {
		$this->field_type = $field_type;

		return true;
	}

}