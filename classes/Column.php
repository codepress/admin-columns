<?php

/**
 * @since NEWVERSION
 */
abstract class AC_Column {

	/**
	 * @var string Unique Name
	 */
	private $name;

	/**
	 * @var string Unique type
	 */
	private $type;

	/**
	 * @var string Label which describes this column
	 */
	private $label;

	/**
	 * @var string Group name
	 */
	private $group;

	/**
	 * @var bool An original column will use the already defined column value and label.
	 */
	private $original;

	/**
	 * @var AC_Settings_Setting[]
	 */
	private $settings;

	/**
	 * @var AC_ListScreen
	 */
	protected $list_screen;

	/**
	 * The options managed by the settings
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Get the unique name of the column
	 *
	 * @since 2.3.4
	 * @return string Column name
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = $name;

		return $this;
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.3.4
	 * @return string Type
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return AC_ListScreen
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * @param AC_ListScreen $list_screen
	 *
	 * @return $this
	 */
	public function set_list_screen( AC_ListScreen $list_screen ) {
		$this->list_screen = $list_screen;

		return $this;
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.4.9
	 * @return string Label of column's type
	 */
	public function get_label() {

		// Original heading
		if ( null === $this->label ) {
			$this->set_label( $this->get_list_screen()->get_original_label( $this->get_type() ) );
		}

		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @since NEWVERSION
	 * @return string Group
	 */
	public function get_group() {
		if ( null === $this->group ) {
			$this->set_group( 'custom' );
		}

		return $this->group;
	}

	/**
	 * @param string $group Group label
	 *
	 * @return $this
	 */
	public function set_group( $group ) {
		$this->group = $group;

		return $this;
	}

	/**
	 * @return string Post type
	 */
	public function get_post_type() {
		return method_exists( $this->list_screen, 'get_post_type' ) ? $this->list_screen->get_post_type() : false;
	}

	/**
	 * @return string Taxonomy
	 */
	public function get_taxonomy() {
		return method_exists( $this->list_screen, 'get_taxonomy' ) ? $this->list_screen->get_taxonomy() : false;
	}

	/**
	 * Return true when a default column has been replaced by a custom column.
	 * An original column will then use the original label and value.
	 *
	 * @since NEWVERSION
	 */
	public function is_original() {
		return $this->original;
	}

	/**
	 * @param bool $boolean
	 *
	 * @return $this
	 */
	public function set_original( $boolean ) {
		$this->original = (bool) $boolean;

		return $this;
	}

	/**
	 * Overwrite this function in child class.
	 * Determine whether this column type should be available
	 *
	 * @since 2.2
	 *
	 * @return bool Whether the column type should be available
	 */
	public function is_valid() {
		return true;
	}

	/**
	 * @param AC_Settings_Setting $setting
	 *
	 * @return $this
	 */
	public function add_setting( AC_Settings_Setting $setting ) {
		$setting->set_values( $this->options );

		$this->settings[ $setting->get_name() ] = $setting;

		foreach ( $setting->get_dependent_settings() as $dependent_setting ) {
			$this->add_setting( $dependent_setting );
		}

		return $this;
	}

	/**
	 * @param string $id Settings ID
	 */
	public function remove_setting( $id ) {
		if ( isset( $this->settings[ $id ] ) ) {
			unset( $this->settings[ $id ] );
		}
	}

	/**
	 * @param string $id
	 *
	 * @return AC_Settings_Setting_User|AC_Settings_Setting_Separator|AC_Settings_Setting_Label
	 */
	public function get_setting( $id ) {
		return $this->get_settings()->get( $id );
	}

	/**
	 * @return AC_Settings_Collection
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$settings = array(
				new AC_Settings_Setting_Type( $this ),
				new AC_Settings_Setting_Label( $this ),
				new AC_Settings_Setting_Width( $this ),
			);

			foreach ( $settings as $setting ) {
				$this->add_setting( $setting );
			}

			$this->register_settings();

			do_action( 'ac/column/settings', $this );
		}

		return new AC_Settings_Collection( $this->settings );
	}

	/**
	 * Register settings
	 */
	protected function register_settings() {
		// Overwrite in child class
	}

	/**
	 * @param string $key
	 *
	 * @return null|string|bool
	 */
	public function get_option( $key ) {
		$options = $this->get_options();

		return isset( $options[ $key ] ) ? $options[ $key ] : null;
	}

	/**
	 * @param array $options
	 *
	 * @return $this
	 */
	public function set_options( array $options ) {
		$this->options = $options;

		return $this;
	}

	/**
	 * Get the current options
	 *
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Apply formatting that is defined in the settings
	 *
	 * A formatter should return a AC_Collection when other formatters
	 * should apply the formatter to each member of the collection
	 *
	 * @param $value
	 *
	 * @return string
	 */
	public function format_value( $value ) {
		foreach ( $this->get_settings() as $setting ) {
			if ( $setting instanceof AC_Settings_FormatInterface ) {

				if ( $value instanceof AC_Collection ) {
					foreach ( $value as $k => $v ) {
						$value->put( $k, $setting->format( $v ) );
					}
				} else {
					$value = $setting->format( $value );
				}

			}
		}

		if ( $value instanceof AC_Collection ) {
			$value = $value->implode( $this->get_separator() );
		}

		return $value;
	}

	/**
	 * @return string
	 */
	private function get_separator() {
		$separator = ', ';

		if ( $setting = $this->get_setting( 'separator' ) ) {
			switch ( $setting->get_separator() ) {
				case 'newline' :
					$separator = '<br/>';
					break;
				case '' :
					$separator = '&nbsp;';
					break;
			}
		}

		return apply_filters( 'ac/column/separator', $separator, $this );
	}

	/**
	 * Enqueue CSS + JavaScript on the admin listings screen!
	 *
	 * This action is called in the admin_head action on the listings screen where your column values are displayed.
	 * Use this action to add CSS + JavaScript
	 *
	 * @since 3.3.4
	 */
	public function scripts() {
		// Overwrite in child class
	}

	/**
	 * Display value
	 *
	 * @param int $object_id ID
	 *
	 * @return mixed
	 */
	public function get_value( $object_id ) {
		return $this->format_value( $this->get_raw_value( $object_id ) );
	}

	/**
	 * Get the raw, underlying value for the column
	 * Not suitable for direct display, use get_value() for that
	 *
	 * @since 2.0.3
	 *
	 * @param int $object_id ID
	 *
	 * @return array|string|bool Raw column value
	 */
	public function get_raw_value( $object_id ) {
		return $object_id;
	}

}