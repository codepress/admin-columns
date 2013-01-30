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
    abstract protected function get_custom();

	/**
	 * Get default WordPress columns for this type
	 *
	 * @since 1.2.1
	 *
	 * @return array WordPress Default Columns.
	 */
	abstract protected function get_default();

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
	public function get_columns() {
		$columns = array();
		
		// get column instance based on $column_name and $storage_key
		if ( $display_columns = $this->get_merged_columns() ) {
			
			foreach ( $display_columns as $column_name => $args ) {
			
				if ( $column = _codepress_get_column_class( $column_name, $this->storage_key, $args ) ) {
					$columns[] = $column;
				}				
			}
		}

		return $columns;
	}
	
	/**
	 * Get Custom Columns
	 *
	 * @since 2.0.0
	 */
	function get_custom_columns() {

		// merge with defaults
		$columns = $this->parse_defaults( $this->get_custom() );

		return apply_filters( "cpac_default_{$this->storage_key}_columns", $columns );
	}

	/**
	 * Get Default columns
	 *
	 * @since 2.0.0
	 */
	function get_default_columns() {
		$columns = $this->get_default();

		return apply_filters( "cpac_default_{$this->storage_key}_columns", $columns );
	}
	
	/**
	 * Get a list of Column options per post type
	 *
	 * @todo: REMOVE
	 * @since 1.0.0
	 *
	 * @return array List that contains each box settings for this type
	 */
	public function __get_column_boxes() {
		// loop throught the active columns
		if ( ! $display_columns = $this->get_merged_columns() )
			return array();

		$boxes = array();

		foreach ( $display_columns as $id => $values ) {

			// defaults
			$box = (object) array(

				// options general
				'label'				=> '',
				'width'				=> 0,
				'state'				=> false,
				'sort'				=> false,
				'filtering'			=> false,

				// options custom
				'image_size'		=> false,
				'image_size_w'		=> 80,
				'image_size_h'		=> 80,

				'field'				=> '',
				'fields'			=> '',
				'field_type'		=> '',
				'before'			=> '',
				'after'				=> '',
				'display_as'		=> '',

				// properties
				'id'				=> $id,
				'type_label'		=> '',
				'enable_sorting'	=> false,
				'enable_filtering'	=> false,

				// properties custom
				'is_image'			=> false,

				// properties box only
				'attr_name'			=> "cpac_options[columns][{$this->storage_key}][{$id}]",
				'attr_for'			=> "cpac-{$this->storage_key}-{$id}",
				'classes'			=> array( "cpac-box-{$id}" ),

				// remove
				'width_descr'		=> __( 'default', CPAC_TEXTDOMAIN ),
				'hide_options'		=> false,
				'is_field'			=> false,


			);

			if ( $this->is_column_active( $values ) ) {
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
			/* 
			if ( $box->width > 0 ) {
				$box->width_descr = $box->width . '%';
			} 
			*/
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

				// User defined value needs to be set
				if ( isset( $values['sort'] ) ) {
					if ( 'off' != $values['sort'] ) {
						$box->sort = true;
					}
				}
				else {
					$box->sort = true;
				}
			}

			// Filtering ( Dropdown )
			if ( isset( $values['options']['enable_filtering'] ) && $values['options']['enable_filtering'] ) {
				$box->enable_filtering = true;

				// User defined value needs to be set
				if ( isset( $values['filtering'] ) && 'off' != $values['filtering'] ) {
					$box->filtering = true;
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
			if ( CPAC_Utility::is_column_customfield( $box->id ) && $keys = $this->get_meta_keys() ) {

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
				if ( ! empty( $values['field_type'] ) && in_array( $values['field_type'], array( 'image', 'library_id' ) ) ) {
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
		$custom_field_types = array(
			''				=> __( 'Default'),
			'image'			=> __( 'Image', CPAC_TEXTDOMAIN ),
			'library_id'	=> __( 'Media Library', CPAC_TEXTDOMAIN ),
			'excerpt'		=> __( 'Excerpt'),
			'array'			=> __( 'Multiple Values', CPAC_TEXTDOMAIN ),
			'numeric'		=> __( 'Numeric', CPAC_TEXTDOMAIN ),
			'date'			=> __( 'Date', CPAC_TEXTDOMAIN ),
			'title_by_id'	=> __( 'Post Title (Post ID\'s)', CPAC_TEXTDOMAIN ),
			'user_by_id'	=> __( 'Username (User ID\'s)', CPAC_TEXTDOMAIN ),
			'checkmark'		=> __( 'Checkmark (true/false)', CPAC_TEXTDOMAIN ),
			'color'			=> __( 'Color', CPAC_TEXTDOMAIN ),
		);

		return apply_filters( 'cpac_field_types', $custom_field_types );
	}

	/**
	 * Get Author Name Types
	 *
	 * @since 1.5.0
	 *
	 * @return array Authorname types.
	 */
	public function get_authorname_types() {
		$authorname_types = array(
			'display_name'		=> __( 'Display Name', CPAC_TEXTDOMAIN ),
			'first_name'		=> __( 'First Name', CPAC_TEXTDOMAIN ),
			'last_name'			=> __( 'Last Name', CPAC_TEXTDOMAIN ),
			'first_last_name' 	=> __( 'First &amp; Last Name', CPAC_TEXTDOMAIN ),
			'nickname'			=> __( 'Nickname', CPAC_TEXTDOMAIN ),
			'username'			=> __( 'Username', CPAC_TEXTDOMAIN ),
			'email'				=> __( 'Email', CPAC_TEXTDOMAIN ),
			'userid'			=> __( 'User ID', CPAC_TEXTDOMAIN )
		);

		return apply_filters( 'cpac_authorname_types', $authorname_types );
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
		$available_columns	= wp_parse_args( $wp_custom_columns, $wp_default_columns );

		// get stored columns
		$stored_columns		= CPAC_Utility::get_stored_columns( $this->storage_key );

		if ( ! $stored_columns ) {
			return $available_columns;
		}

		// All column settings are stored in de DB. We retrieved the stored columns settings
		// and we will match them against the available columns.
		// When there is a difference between the available columns and the stored columns
		// we will need to remove these, such as disabled plugins.
		// Loop through these column_names and unset them.
		$diff = array_diff( array_keys( $stored_columns ), array_keys( $available_columns ) );

		if ( ! empty( $diff ) && is_array( $diff ) ) {
			foreach ( $diff as $column_name ){

				// Columns that can have multiple instances should
				// Custom Field Columns can have multiple column names ( column-meta-xxx ).
				// These should not be removed.
				if ( CPAC_Utility::is_column_customfield( $column_name ) )
					continue;

				unset( $stored_columns[$column_name] );
			}
		}

		// Add the static options to the Columns.
		foreach ( $stored_columns as $column_name => $v ) {

			// get column meta options from custom columns
			if ( CPAC_Utility::is_column_customfield( $column_name ) && ! empty( $wp_custom_columns['column-meta-1']['options'] ) ) {
				$stored_columns[$column_name]['options'] = $wp_custom_columns['column-meta-1']['options'];
			}

			// add static options
			elseif ( isset( $available_columns[$column_name]['options'] ) ) {
				$stored_columns[$column_name]['options'] = $available_columns[$column_name]['options'];
			}

			unset( $available_columns[$column_name] );
		}

		// merge all
		return wp_parse_args( $stored_columns, $available_columns );
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
		foreach ( (array) $columns as $column_name => $label ) {
			$hide_options 	= false;
			$type_label 	= $label;

			// comment exception
			if ( 'comments' == $column_name ) {
				$label 			= '';
				$type_label 	= __( 'Comments', CPAC_TEXTDOMAIN );
				$hide_options 	= true;
			}

			// user icon exception
			if ( 'icon' == $column_name ) {
				$type_label 	= __( 'Icon', CPAC_TEXTDOMAIN );
			}

			// taxonomy exception
			if ( CPAC_Utility::is_column_taxonomy( $column_name ) ) {
				$type_label 	= __( 'Taxonomy', CPAC_TEXTDOMAIN ) . ': ' . $label;
			}

			$uniform_columns[$column_name] = array(
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
				'type_label'		=> __( 'Custom', CPAC_TEXTDOMAIN ),
				'hide_options'		=> false,
				'class'				=> 'cpac-box-custom',
				'enable_sorting'	=> true,
				'enable_filtering'	=> false,
				'is_dynamic'		=> false // custom fields and taxonomies
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
	 * @todo: filter_preset_columns?
	 * @since 1.1
	 *
	 * @param array $columns Column Headings.
	 * @return array CPAC Column Headings.
	 */
	public function add_columns_headings( $columns ) {

		$stored_columns	= CPAC_Utility::get_stored_columns( $this->storage_key );

		if ( ! $stored_columns ) {
			return $columns;
		}

		$columns_headings = array();

		// Make sure the checkbox is add the beginning of the Column Headings when it is available
		if ( isset( $columns['cb'] ) ) {
			$columns_headings = array( 'cb' => $columns['cb'] ) + $columns_headings;
			unset( $columns['cb'] );
		}

		// Add the stored columns to the Columns headings.
		foreach ( $stored_columns as $column_name => $values ) {
			if ( ! $this->is_column_active( $values ) )
				continue;

			$columns_headings[$column_name] = $values['label'];
		}

		// Some 3rd parth columns will no be stored. These still need to be added
		// to the column headings. We check the default stored columns and every columns
		// that is new will be added.
		if ( $options = get_option( 'cpac_options_default' ) ) {

			// Get the default columns that have been stored on the settings page.
			$stored_wp_default_columns = $options[$this->storage_key];

			// ... get the 3rd party columns that have not been saved...
			$dif_columns = array_diff( array_keys( $columns ), array_keys( $stored_wp_default_columns ) );

			// ... add those columns to the column headings
			if ( $dif_columns ) {
				foreach ( $dif_columns as $column_name ) {
					$columns_headings[$column_name] = $columns[$column_name];
				}
			}
		}

		return $columns_headings;
	}

	/**
	 * is_column_active
	 *
	 * @since 2.0.0
	 *
	 * @return bool true|false
	 */
	function is_column_active( $column ) {
		if( isset( $column['state'] ) && 'on' == $column['state'] )
			return true;

		return false;
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
				if ( isset( $vars['options']['enable_sorting'] ) && $vars['options']['enable_sorting'] && isset( $vars['sort'] ) && 'on' == $vars['sort'] ){

					$columns[$id] = CPAC_Utility::sanitize_string( $vars['label'] );
				}
			}
		}

		return $columns;
	}
}