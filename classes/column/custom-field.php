<?php
defined( 'ABSPATH' ) or die();

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all storage models supporting WordPress' default way of handling meta data.
 *
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 *
 * @since 1.0
 */
class CPAC_Column_Custom_Field extends CPAC_Column implements CPAC_Column_Custom_FieldInterface {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-meta';
		$this->properties['label'] = __( 'Custom Field', 'codepress-admin-columns' );
		$this->properties['classes'] = 'cpac-box-metafield';
		$this->properties['group'] = __( 'Custom Field', 'codepress-admin-columns' );
		$this->properties['use_before_after'] = true;

		// Default options
		$this->options['image_size'] = 'cpac-custom';
		$this->options['image_size_w'] = 80;
		$this->options['image_size_h'] = 80;
		$this->options['excerpt_length'] = 15;
	}

	public function get_field_key() {
		$field = $this->get_option( 'field' );

		return substr( $field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden', '', $field ) : $field;
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field_type() {
		return $this->get_option( 'field_type' );
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
	 * @see CPAC_Column::sanitize_options()
	 * @since 1.0
	 */
	public function sanitize_options( $options ) {
		if ( empty( $options['date_format'] ) ) {
			$options['date_format'] = get_option( 'date_format' );
		}

		return $options;
	}

	/**
	 * Get Custom FieldType Options - Value method
	 *
	 * @since 1.0
	 *
	 * @return array Customfield types.
	 */
	public function get_custom_field_types() {

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
	 * Get First ID from array
	 *
	 * @since 1.0
	 *
	 * @param string $meta
	 *
	 * @return string Titles
	 */
	public function get_ids_from_meta( $meta ) {

		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace( ' ', '', $meta ) );

		$ids = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false ) {
			$ids = explode( ',', $meta );
		}
		elseif ( is_numeric( $meta ) ) {
			$ids[] = $meta;
		}

		return $ids;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
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
	 * @since 2.5.6
	 */
	public function get_date_by_string( $date_string ) {
		return $this->get_date( $date_string, $this->get_option( 'date_format' ) );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 1.0
	 */
	public function get_value( $id ) {
		$value = '';

		$raw_value = $this->get_raw_value( $id );
		$raw_string = $this->recursive_implode( ', ', $raw_value );

		switch ( $this->get_field_type() ) :
			case "image" :
			case "library_id" :
				$value = implode( $this->get_thumbnails( $raw_string, array(
					'image_size'   => $this->get_option( 'image_size' ),
					'image_size_w' => $this->get_option( 'image_size_w' ),
					'image_size_h' => $this->get_option( 'image_size_h' ),
				) ) );
				break;

			case "excerpt" :
				$value = $this->get_shortened_string( $raw_value, $this->get_option( 'excerpt_length' ) );
				break;

			case "date" :
				$value = $this->get_date_by_string( $raw_value );
				break;

			case "link" :
				if ( filter_var( $raw_value, FILTER_VALIDATE_URL ) || preg_match( '/[^\w.-]/', $raw_value ) ) {
					$label = $this->get_option( 'link_label' );
					if ( ! $label ) {
						$label = $raw_value;
					}
					$value = '<a href="' . $raw_value . '">' . $label . '</a>';
				}
				break;

			case "title_by_id" :
				$titles = array();
				if ( $ids = $this->get_ids_from_meta( $raw_string ) ) {
					foreach ( (array) $ids as $id ) {
						if ( $title = $this->get_post_title( $id ) ) {
							$link = get_edit_post_link( $id );
							$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
						}
					}
				}
				$value = implode( '<span class="cpac-divider"></span>', $titles );
				break;

			case "user_by_id" :
				$names = array();
				if ( $ids = $this->get_ids_from_meta( $raw_string ) ) {
					foreach ( (array) $ids as $id ) {
						if ( $username = $this->get_username_by_id( $id ) ) {
							$link = get_edit_user_link( $id );
							$names[] = $link ? "<a href='{$link}'>{$username}</a>" : $username;
						}
					}
				}
				$value = implode( '<span class="cpac-divider"></span>', $names );
				break;

			case "term_by_id" :
				if ( is_array( $raw_value ) && isset( $raw_value['term_id'] ) && isset( $raw_value['taxonomy'] ) ) {
					$value = AC()->helper->term->display( (array) get_term_by( 'id', $raw_value['term_id'], $raw_value['taxonomy'] ) );
				}
				break;

			case "checkmark" :
				$is_true = ( ! empty( $raw_value ) && 'false' !== $raw_value && '0' !== $raw_value );

				$value = $this->get_icon_yes_or_no( $is_true );
				break;

			case "color" :
				$value = $raw_value && is_scalar( $raw_value ) ? $this->get_color_for_display( $raw_value ) : $this->get_empty_char();
				break;

			case "count" :
				$value = $raw_value ? count( $raw_value ) : $this->get_empty_char();
				break;

			default :
				$value = $raw_string;

		endswitch;

		if ( ! $value ) {
			$value = $this->get_empty_char();
		}

		/**
		 * Filter the display value for Custom Field columns
		 *
		 * @param mixed $value Custom field value
		 * @param int $id Object ID
		 * @param object $this Column instance
		 */
		$value = apply_filters( 'cac/column/meta/value', $value, $id, $this );

		return $value;
	}

	/**
	 * @since 2.4.7
	 */
	public function get_meta_keys() {
		return $this->get_storage_model()->get_meta_keys();
	}

	private function get_grouped_field_options() {
		$grouped_options = array();

		if ( $keys = $this->get_meta_keys() ) {
			$grouped_options = array(
				'hidden' => array(
					'title'   => __( 'Hidden Custom Fields', 'codepress-admin-columns' ),
					'options' => ''
				),
				'public' => array(
					'title'   => __( 'Custom Fields', 'codepress-admin-columns' ),
					'options' => ''
				)
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

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 1.0
	 */
	public function display_settings() {

		// DOM can get overloaded when dropdown contains to many custom fields. Use this filter to replace the dropdown with a text input.
		if ( apply_filters( 'cac/column/meta/use_text_input', false ) ) :
			$this->form_field( array(
				'type'        => 'text',
				'name'        => 'field',
				'label'       => __( "Custom Field", 'codepress-admin-columns' ),
				'description' => __( "Enter your custom field key.", 'codepress-admin-columns' )
			) );
		else :
			$this->form_field( array(
				'type'            => 'select',
				'name'            => 'field',
				'label'           => __( 'Custom Field', 'codepress-admin-columns' ),
				'description'     => __( 'Select your custom field.', 'codepress-admin-columns' ),
				'no_result'       => __( 'No custom fields available.', 'codepress-admin-columns' ) . ' ' . sprintf( __( 'Please create a %s item first.', 'codepress-admin-columns' ), '<strong>' . $this->get_storage_model()->singular_label . '</strong>' ),
				'grouped_options' => $this->get_grouped_field_options(),
			) );
		endif;

		$this->form_field( array(
			'type'           => 'select',
			'name'           => 'field_type',
			'label'          => __( 'Field Type', 'codepress-admin-columns' ),
			'description'    => __( 'This will determine how the value will be displayed.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->get_field_type() . '</em>',
			'options'        => $this->get_custom_field_types(),
			'refresh_column' => true,
		) );

		switch ( $this->get_field_type() ) {
			case 'date' :
				$this->display_field_date_format();
				break;
			case 'image' :
			case 'library_id' :
				$this->display_field_preview_size();
				break;
			case 'excerpt' :
				$this->display_field_excerpt_length();
				break;
			case 'link' :
				$this->display_field_link_label();
				break;
		}
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
		_deprecated_function( __CLASS__ . '::' . __FUNCTION__ . '()', '2.5.6', __CLASS__ . '::' . 'recursive_implode()' );

		return $this->recursive_implode( ', ', $this->get_raw_value( $id ) );
	}
}