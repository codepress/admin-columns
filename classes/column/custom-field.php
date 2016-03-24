<?php

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all storage models supporting WordPress' default way of handling meta data.
 *
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 *
 * @since 1.0
 */
class CPAC_Column_Custom_Field extends CPAC_Column {

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

		// Options
		$this->options['field'] = '';
		$this->options['field_type'] = '';
		$this->options['before'] = '';
		$this->options['after'] = '';

		$this->options['image_size'] = '';
		$this->options['image_size_w'] = 80;
		$this->options['image_size_h'] = 80;

		$this->options['excerpt_length'] = 15;

		$this->options['link_label'] = '';

		$this->options['date_format'] = '';
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
	public function get_field_type() {
		return $this->options->field_type;
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
			''            => __( 'Default', 'codepress-admin-columns' ),
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

		// deprecated. do not use, will be removed.
		$custom_field_types = apply_filters( 'cpac_custom_field_types', $custom_field_types );

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

		// var
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
	 * Get Title by ID - Value method
	 *
	 * @since 1.0
	 *
	 * @param string $meta
	 *
	 * @return string Titles
	 */
	private function get_titles_by_id( $ids ) {

		$titles = array();

		// display title with link
		if ( $ids = $this->get_ids_from_meta( $ids ) ) {
			foreach ( (array) $ids as $id ) {

				if ( ! is_numeric( $id ) ) {
					continue;
				}

				if ( $title = $this->get_post_title( $id ) ) {
					$link = get_edit_post_link( $id );
					$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
				}
			}
		}

		return implode( '<span class="cpac-divider"></span>', $titles );
	}

	/**
	 * @since 2.4.9
	 */
	private function get_link_by_meta( $meta ) {
		$label = $meta;
		if ( filter_var( $meta, FILTER_VALIDATE_URL ) || preg_match( '/[^\w.-]/', $meta ) ) {
			if ( ! empty( $this->options->link_label ) ) {
				$label = $this->options->link_label;
			}
			$meta = '<a href="' . $meta . '">' . $label . '</a>';
		}

		return $meta;
	}

	/**
	 * Get Users by ID - Value method
	 *
	 * @since 1.0
	 *
	 * @param string $meta
	 *
	 * @return string Users
	 */
	private function get_users_by_id( $meta ) {

		$names = array();

		// display username
		if ( $ids = $this->get_ids_from_meta( $meta ) ) {
			foreach ( (array) $ids as $id ) {
				if ( ! is_numeric( $id ) ) {
					continue;
				}

				$userdata = get_userdata( $id );
				if ( is_object( $userdata ) && ! empty( $userdata->display_name ) ) {

					// link
					$link = get_edit_user_link( $id );

					$names[] = $link ? "<a href='{$link}'>{$userdata->display_name}</a>" : $userdata->display_name;
				}
			}
		}

		return implode( '<span class="cpac-divider"></span>', $names );
	}

	/**
	 * Get Terms by ID - Value method
	 *
	 * @since 2.3.2
	 *
	 * @param array $meta_value Term ID's
	 *
	 * @return string Terms
	 */
	public function get_terms_by_id( $meta_value ) {
		// as used by Pods, @todo
		if ( ! is_array( $meta_value ) || ! isset( $meta_value['term_id'] ) || ! isset( $meta_value['taxonomy'] ) ) {
			return false;
		}

		return $this->get_terms_for_display( $meta_value['term_id'], $meta_value['taxonomy'] );
	}

	/**
	 * Get meta value
	 *
	 * @since 2.0
	 *
	 * @param string $meta Contains Meta Value
	 * @param int $id Optional Object ID
	 *
	 * @return string Users
	 */
	public function get_value_by_meta( $meta, $id = null ) {

		switch ( $this->options->field_type ) :

			case "image" :
			case "library_id" :
				$meta = implode( $this->get_thumbnails( $meta, array(
					'image_size'   => $this->options->image_size,
					'image_size_w' => $this->options->image_size_w,
					'image_size_h' => $this->options->image_size_h,
				) ) );
				break;

			case "excerpt" :
				$meta = $this->get_shortened_string( $meta, $this->options->excerpt_length );
				break;

			case "date" :
				$meta = $this->get_date( $meta, $this->options->date_format );
				break;

			case "link" :
				$meta = $this->get_link_by_meta( $this->get_raw_value( $id ) );
				break;

			case "title_by_id" :
				$meta = $this->get_titles_by_id( $meta );
				break;

			case "user_by_id" :
				$meta = $this->get_users_by_id( $meta );
				break;

			case "term_by_id" :
				$meta = $this->get_terms_by_id( $this->get_raw_value( $id ) );
				break;

			case "checkmark" :
				$checkmark = '<span class="dashicons dashicons-yes cpac_status_yes"></span>';

				if ( empty( $meta ) || 'false' === $meta || '0' === $meta ) {
					$checkmark = '<span class="dashicons dashicons-no cpac_status_no"></span>';
				}

				$meta = $checkmark;
				break;

			case "color" :
				if ( ! empty( $meta ) ) {
					$meta = $this->get_color_for_display( $meta );
				}
				break;

			case "count" :
				if ( $count = $this->get_raw_value( $id, false ) ) {
					$meta = count( $count );
				}
				break;

		endswitch;

		return $meta;
	}

	/**
	 * Get Field key
	 *
	 * @since 2.0.3
	 *
	 * @param string Custom Field Key
	 */
	public function get_field_key() {

		return substr( $this->options->field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden', '', $this->options->field ) : $this->options->field;
	}

	/**
	 * Get meta by ID
	 *
	 * @since 1.0
	 *
	 * @param int $id ID
	 *
	 * @return string Meta Value
	 */
	public function get_meta_by_id( $id ) {

		$meta = $this->get_raw_value( $id );

		// try to turn any array into a comma seperated string for further use
		if ( ( 'array' == $this->options->field_type && is_array( $meta ) ) || is_array( $meta ) ) {
			$meta = $this->recursive_implode( ', ', $meta );
		}

		if ( ! is_string( $meta ) && ! is_numeric( $meta ) ) {
			return false;
		}

		return $meta;
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
	 * @see CPAC_Column::get_value()
	 * @since 1.0
	 */
	public function get_value( $id ) {
		$value = '';

		if ( $meta = $this->get_meta_by_id( $id ) ) {
			$value = $this->get_value_by_meta( $meta, $id );
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

	public function get_meta_keys_list() {
		$list = false;

		if ( $keys = $this->get_meta_keys() ) {
			$lists = array();
			foreach ( $keys as $field ) {
				if ( substr( $field, 0, 10 ) == "cpachidden" ) {
					$lists['hidden'][] = $field;
				}
				else {
					$lists['public'][] = $field;
				}
			}
			krsort( $lists ); // public first

			$list = '<select name="' . $this->get_attr_name( 'field' ) . '" id="' . $this->get_attr_id( 'field' ) . '">';
			foreach ( $lists as $type => $fields ) {
				$list .= "<optgroup label='" . ( 'hidden' == $type ? __( 'Hidden Custom Fields', 'codepress-admin-columns' ) : __( 'Custom Fields', 'codepress-admin-columns' ) ) . "'>";
				foreach ( $fields as $field ) {
					$list .= "<option value='{$field}'" . selected( $field, $this->options->field, false ) . ">" . str_replace( 'cpachidden', '', $field ) . "</option>";
				}
				$list .= "</optgroup>";
			}
			$list .= '</select>';
		}

		return $list;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 1.0
	 */
	public function display_settings() {

		// DOM can get overloaded when dropdown contains to many custom fields. Use this filter to replace the dropdown with a text input.
		if ( apply_filters( 'cac/column/meta/use_text_input', false ) ) :
			$this->display_field_text( 'field', __( "Custom Field", 'codepress-admin-columns' ), __( "Enter your custom field key.", 'codepress-admin-columns' ) );
		else :
			?>
			<tr class="column_field">
				<?php $this->label_view( __( "Custom Field", 'codepress-admin-columns' ), __( "Select your custom field.", 'codepress-admin-columns' ), 'field' ); ?>
				<td class="input">
					<?php
					if ( $list = $this->get_meta_keys_list() ) {
						echo $list;
					}
					else {
						_e( 'No custom fields available.', 'codepress-admin-columns' ); ?><?php printf( __( 'Please create a %s item first.', 'codepress-admin-columns' ), '<strong>' . $this->get_storage_model()->singular_label . '</strong>' );
					}
					?>
				</td>
			</tr>
		<?php endif; ?>

		<tr class="column_field_type" data-refresh="1">
			<?php $this->label_view( __( "Field Type", 'codepress-admin-columns' ), __( 'This will determine how the value will be displayed.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->options->field_type . '</em>', 'field_type' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'field_type' ); ?>" id="<?php $this->attr_id( 'field_type' ); ?>">
					<?php foreach ( $this->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
						<option
							value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $this->options->field_type ) ?>><?php echo $fieldtype; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<?php
		switch ( $this->options->field_type ) {
			case 'date':
				$this->display_field_date_format();
				break;
			case 'image':
			case 'library_id':
				$this->display_field_preview_size();
				break;
			case 'excerpt':
				$this->display_field_excerpt_length();
				break;
			case 'link':
				$this->display_field_link_label();
				break;
		}
	}
}