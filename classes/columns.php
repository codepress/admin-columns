<?php

abstract class CPAC_Columns
{
	/**
     * Storage key
	 *
	 * Key by which the settings are saved to the Database. Every WordPress Column type has it's unique key.
	 * Supported types are comments, links, users, media and posts. The first 4 get prefixed with 'wp-', except
	 * for posts. They have their posttype as storage key.
	 *
	 * @since 1.5.0
	 *
     * @var string Storage Key.
     */
    public $storage_key;

	/**
     * Get the custom columns for this type
     *
     * @since 1.3.0
	 *
	 * @return array Custom Columns.
     */
    abstract protected function get_custom_columns();

	/**
	 * Get default WordPress columns for this type
	 *
	 * @since 1.2.1
	 *
	 * @return array WordPress Default Columns.
	 */
	abstract protected function get_default_columns();

	/**
     * Get the label for this type
     *
     * @since 1.3.0
	 *
	 * @return string Singular Name.
     */
    abstract protected function get_label();

	/**
     * Returns the meta keys that are associated with an attachment.
	 *      *
     * Ignores keys prefixed by a '_', as they are meant to be private.
     *
     * @since 1.0.0
	 *
	 * @return array Meta Keys.
     */
    abstract public function get_meta_keys();

	/**
	 * Get a list of Column options per post type
	 *
	 * @since 1.0.0
	 *
	 * @return array List that contains each box settings for this type
	 */
	public function get_column_boxes() {
		// loop throught the active columns
		if ( ! $display_columns = $this->get_merged_columns() )
			return array();

		$boxes = array();

		foreach ( $display_columns as $id => $values ) {

			// defaults
			$box = (object) array(
				'id' 			=> $id,
				'attr_name'		=> "cpac_options[columns][{$this->storage_key}][{$id}]",
				'attr_for'		=> "cpac-{$this->storage_key}-{$id}",
				'classes'		=> array( "cpac-box-{$id}" ),
				'state' 		=> false,
				'type_label' 	=> '',
				'label' 		=> '',
				'width' 		=> 0,
				'width_descr' 	=> __( 'default', CPAC_TEXTDOMAIN ),
				'hide_options'	=> false,
				'sort'			=> false,
				'enable_sorting'=> false,
				'is_image'		=> false,
				'image_size'	=> false,
				'image_size_w'	=> 80,
				'image_size_h'	=> 80,
				'is_field'		=> false,
				'field'			=> '',
				'fields'		=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'display_as'	=> '',
				'source_type'	=> ''
			);

			if ( isset( $values['state'] ) && 'on' == $values['state'] ) {
				$box->state 	= 'on';
				$box->classes[] = 'active';
			}
			if ( isset( $values['options']['type_label'] ) ) {
				$box->type_label = $values['options']['type_label'];
			}
			if ( isset( $values['label'] ) ) {
				$box->label = $values['label'];
			}
			if ( isset( $values['width'] ) ) {
				$box->width	= $values['width'];
			}
			if ( $box->width > 0 ) {
				$box->width_descr = $box->width . '%';
			}
			if ( ! empty( $values['options']['hide_options'] ) || strpos( $box->label, '<img' ) !== false ) {
				$box->hide_options = true;
			}
			if ( ! empty($values['options']['class']) ) {
				$box->classes[] = $values['options']['class'];
			}

			$box->classes = implode(' ', $box->classes);

			// Sortorder
			if ( isset( $values['options']['enable_sorting'] ) && $values['options']['enable_sorting'] ) {
				$box->enable_sorting = true;

				if ( isset( $values['sort'] ) ) {
					if ( 'off' != $values['sort'] ) {
						$box->sort = true;
					}
				}
				else {
					$box->sort = true;
				}
			}

			// Image
			if ( isset( $values['options']['is_image'] ) && $values['options']['is_image'] ) {
				$box->is_image = true;
			}

			// Image Size
			if ( isset($values['image_size']) ) {
				$box->image_size = ! empty( $values['image_size'] ) ? $values['image_size'] : 'thumbnail';
				if ( ! empty( $values['image_size_w'] ) ) {
					$box->image_size_w 	= $values['image_size_w'];
				}
				if ( ! empty( $values['image_size_h'] ) ) {
					$box->image_size_h 	= $values['image_size_h'];
				}
			}

			// Custom Fields
			if ( CPAC_Utility::is_column_meta( $box->id ) && $keys = $this->get_meta_keys() ) {

				$box->is_field 	= true;
				$box->fields 	= $keys;

				if ( ! empty( $values['field'] ) ) {
					$box->field = $values['field'];
				}
				if ( ! empty( $values['field_type'] ) ) {
					$box->field_type = $values['field_type'];
				}
				if ( ! empty( $values['before'] ) ) {
					$box->before = $values['before'];
				}
				if ( ! empty( $values['after'] ) ) {
					$box->after  = $values['after'];
				}
				if ( ! empty( $values['field_type'] ) && 'image' == $values['field_type'] ) {
					$box->is_image = true;
				}
			}

			// Author Names
			elseif ( 'column-author-name' == $box->id && ! empty( $values['display_as'] ) ) {
				$box->display_as = $values['display_as'];
			}

			$boxes[] = $box;
		}

		return $boxes;
	}

	/**
	 * Get Custom FieldType Options
	 *
	 * @since 1.5.0
	 *
	 * @return array Customfield types.
	 */
	public function get_custom_field_types() {
		return apply_filters( 'cpac-field-types', array(
			''				=> __( 'Default'),
			'image'			=> __( 'Image', CPAC_TEXTDOMAIN ),
			'excerpt'		=> __( 'Excerpt'),
			'array'			=> __( 'Multiple Values', CPAC_TEXTDOMAIN ),
			'numeric'		=> __( 'Numeric', CPAC_TEXTDOMAIN ),
			'date'			=> __( 'Date', CPAC_TEXTDOMAIN ),
			'title_by_id'	=> __( 'Post Title (Post ID\'s)', CPAC_TEXTDOMAIN ),
			'user_by_id'	=> __( 'Username (User ID\'s)', CPAC_TEXTDOMAIN ),
			'checkmark'		=> __( 'Checkmark (true/false)', CPAC_TEXTDOMAIN ),
			'color'			=> __( 'Color', CPAC_TEXTDOMAIN ),
		));
	}

	/**
	 * Get Author Name Types
	 *
	 * @since 1.5.0
	 *
	 * @return array Authorname types.
	 */
	public function get_authorname_types() {
		return apply_filters( 'cpac-authorname-types', array(
			'display_name'		=> __( 'Display Name', CPAC_TEXTDOMAIN ),
			'first_name'		=> __( 'First Name', CPAC_TEXTDOMAIN ),
			'last_name'			=> __( 'Last Name', CPAC_TEXTDOMAIN ),
			'first_last_name' 	=> __( 'First &amp; Last Name', CPAC_TEXTDOMAIN ),
			'nickname'			=> __( 'Nickname', CPAC_TEXTDOMAIN ),
			'username'			=> __( 'Username', CPAC_TEXTDOMAIN ),
			'email'				=> __( 'Email', CPAC_TEXTDOMAIN ),
			'userid'			=> __( 'User ID', CPAC_TEXTDOMAIN )
		));
	}

	/**
	 * Get merged columns
	 *
	 * Contains a combination of WordPress default columns, CPAC custom columns and Stored columns.
	 *
	 * @since 1.0.0
	 *
	 * @return array Columns.
	 */
	function get_merged_columns() {
		// get added and WP columns
		$wp_default_columns = $this->get_default_columns();
		$wp_custom_columns  = $this->get_custom_columns();

		// merge
		$default_columns	= wp_parse_args( $wp_custom_columns, $wp_default_columns );

		// Get saved columns
		if ( ! $db_columns = CPAC_Utility::get_stored_columns( $this->storage_key ) )
			return $default_columns;

		// let's remove any unavailable columns.. such as disabled plugins
		$diff = array_diff( array_keys( $db_columns ), array_keys( $default_columns ) );

		// check for differences
		if ( ! empty( $diff ) && is_array( $diff ) ) {
			foreach ( $diff as $column_name ){
				// make an exception for column-meta-xxx
				if ( CPAC_Utility::is_column_meta( $column_name ) )
					continue;

				unset( $db_columns[$column_name] );
			}
		}

		// loop throught the active columns
		foreach ( $db_columns as $id => $values ) {

			// get column meta options from custom columns
			if ( CPAC_Utility::is_column_meta( $id ) && ! empty( $wp_custom_columns['column-meta-1']['options'] ) ) {
				$db_columns[$id]['options'] = $wp_custom_columns['column-meta-1']['options'];
			}

			// add static options
			elseif ( isset( $default_columns[$id]['options'] ) ) {
				$db_columns[$id]['options'] = $default_columns[$id]['options'];
			}

			unset( $default_columns[$id] );
		}

		// merge all
		return wp_parse_args( $db_columns, $default_columns );
	}

	/**
	 * Build uniform format for all columns
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns WordPress default columns.
	 * @return array CPAC Columns format.
	 */
	public function get_uniform_format( $columns ) {
		// we remove the checkbox column as an option...
		if ( isset( $columns['cb'] ) )
			unset( $columns['cb'] );

		// change to uniform format
		$uniform_columns = array();
		foreach ( (array) $columns as $id => $label ) {
			$hide_options 	= false;
			$type_label 	= $label;

			// comment exception
			if ( 'comments' == $id ) {
				$label 			= '';
				$type_label 	= __( 'Comments', CPAC_TEXTDOMAIN );
				$hide_options 	= true;
			}

			// user icon exception
			if ( $id == 'icon' ) {
				$type_label 	= __( 'Icon', CPAC_TEXTDOMAIN );
			}

			$uniform_columns[$id] = array(
				'label'			=> $label,
				'state'			=> 'on',
				'options'		=> array(
					'type_label' 	=> $type_label,
					'hide_options'	=> $hide_options,
					'class'			=> 'cpac-box-wp-native',
				)
			);
		}
		return $uniform_columns;
	}

	/**
	 * Parse defaults
	 *
	 * @since 1.1.0
	 *
	 * @param array $columns Columns raw.
	 * @return array Columns with merged defaults.
	 */
	public function parse_defaults( $columns ) {
		// default arguments
		$defaults = array(

			// stored values
			'label'			=> '', // custom label
			'state' 		=> '', // display state
			'width' 		=> '', // column width

			// static values
			'options'		=> array(
				'type_label'	=> __( 'Custom', CPAC_TEXTDOMAIN ),
				'hide_options'	=> false,
				'class'			=> 'cpac-box-custom',
				'enable_sorting'=> true,
			)
		);

		// parse args
		foreach ( $columns as $k => $column ) {
			$c[$k] = wp_parse_args( $column, $defaults );

			// parse options args
			if ( isset( $column['options'] ) )
				$c[$k]['options'] = wp_parse_args( $column['options'], $defaults['options'] );

			// set type label
			if ( empty( $column['options']['type_label'] ) && ! empty( $column['label'] ) ) {
				$c[$k]['options']['type_label']	= $column['label'];
			}
		}

		return $c;
	}

	/**
	 * Maybe add hidden meta
	 *
	 * @since 1.5
	 *
	 * @param array $fields Custom fields.
	 * @return array Custom fields.
	 */
	function maybe_add_hidden_meta( $fields ) {
		if ( ! $fields )
			return false;

		$combined_fields = array();

		$use_hidden_meta = apply_filters( 'cpac_use_hidden_custom_fields', false );

		// filter out hidden meta fields
		foreach ( $fields as $field ) {

			// give hidden fields a prefix for identifaction
			if ( $use_hidden_meta && substr( $field[0], 0, 1 ) == "_") {
				$combined_fields[] = 'cpachidden'.$field[0];
			}

			// non hidden fields are saved as is
			elseif ( substr( $field[0], 0, 1 ) != "_" ) {
				$combined_fields[] = $field[0];
			}
		}

		if ( empty( $combined_fields ) )
			return false;

		return $combined_fields;
	}

	/**
	 * Add managed columns by Type
	 *
	 * Triggerd by WordPress apply_filters( manage_{$screen->id}_columns, $columns );
	 *
	 * @since 1.1
	 *
	 * @param array $columns Column Headings.
	 * @return array CPAC Column Headings.
	 */
	public function add_columns_headings( $columns ) {
		if ( ! $db_columns	= CPAC_Utility::get_stored_columns( $this->storage_key ) )
			return $columns;

		// filter already loaded columns by plugins
		$set_columns = $this->filter_preset_columns( $columns );

		// loop through columns
		foreach ( $db_columns as $id => $values ) {
			// is active
			if ( isset($values['state']) && $values['state'] == 'on' ){

				$label = $values['label'];

				// exception for comments
				if ( 'comments' == $id ) {
					$label = $this->get_comment_icon();
				}

				// register format
				$set_columns[$id] = $label;
			}
		}

		return $set_columns;
	}

	/**
	 * Filter preset columns.
	 *
	 * Returns the difference from the stored WordPress default columns and the current loading columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Columns
	 * @todo: refactor
	 */
	public function filter_preset_columns( $columns ) {
		if ( ! $options = get_option( 'cpac_options_default' ) )
			return $columns;

		// we use the wp default columns for filtering...
		$stored_wp_default_columns = $options[$this->storage_key];

		// ... the ones that are set by plugins, theme functions and such.
		$dif_columns = array_diff( array_keys( $columns ), array_keys( $stored_wp_default_columns ) );

		// we add those to the columns
		$pre_columns = array();
		if ( $dif_columns ) {
			foreach ( $dif_columns as $column ) {
				$pre_columns[$column] = $columns[$column];
			}
		}

		return $pre_columns;
	}

	/**
	 * Add managed columns by Type
	 *
	 * @since 1.4.6.5
	 *
	 * @return string
	 */
	function get_comment_icon() {
		return "<span class='vers'><img src='" . trailingslashit( get_admin_url() ) . 'images/comment-grey-bubble.png' . "' alt='Comments'></span>";
	}

	/**
	 * Get sortable columns
	 *
	 * @since 1.1
	 *
	 * @return array Columns that support sorting.
	 */
	public function get_sortable_columns() {
		$columns = array();

		if ( $display_columns = $this->get_merged_columns() ) {
			foreach ( $display_columns as $id => $vars ) {
				if ( isset( $vars['options']['enable_sorting'] ) && $vars['options']['enable_sorting'] ){

					$columns[$id] = CPAC_Utility::sanitize_string( $vars['label'] );
				}
			}
		}

		return $columns;
	}
}