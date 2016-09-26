<?php
defined( 'ABSPATH' ) or die();

/**
 * Storage Model
 *
 * @since 2.0
 */
abstract class AC_StorageModel {

	/**
	 * @since 2.0
	 */
	public $label;

	/**
	 * @since 2.3.5
	 */
	public $singular_label;

	/**
	 * Identifier for Storage Model; Post type etc.
	 *
	 * @since 2.0
	 */
	public $key;

	/**
	 * Type of storage model; Post, Media, User or Comments
	 *
	 * @since 2.0
	 */
	public $type;

	/**
	 * Meta type of storage model; post, user, comment. Mostly used for custom field data.
	 *
	 * @since 3.0
	 */
	public $meta_type;

	/**
	 * Groups the storage model in the menu.
	 *
	 * @since 2.0
	 */
	public $menu_type;

	/**
	 * @since 2.0
	 * @var string
	 */
	public $page;

	/**
	 * @since 2.4.10
	 * @var string
	 */
	public $subpage;

	/**
	 * @since NEWVERSION
	 * @var string
	 */
	public $post_type;

	/**
	 * @since NEWVERSION
	 * @var string
	 */
	protected $table_classname;

	/**
	 * @since 2.5
	 * @var string
	 */
	public $screen;

	/**
	 * @var AC_Settings $settings
	 */
	private $settings;

	/**
	 * @var AC_Columns $columns
	 */
	private $columns;

	/**
	 * Set the above variables of the object
	 *
	 * @return void
	 */
	abstract function init();

	/**
	 * Contains the hook that contains the manage_value callback
	 *
	 * @return void
	 */
	abstract function init_manage_value();

	/**
	 * @since 2.4.4
	 */
	function __construct() {
		$this->menu_type = __( 'Other', 'codepress-admin-columns' );

		$this->init();
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return apply_filters( 'ac/storage_model/label', $this->label, $this );
	}

	/**
	 * @param string $key
	 */
	public function set_key( $key ) {
		$this->key = $key;
	}

	/**
	 * Return a single object based on it's ID (post, user, comment etc.)
	 *
	 * @since NEWVERSION
	 * @return mixed
	 */
	protected function get_object_by_id( $id ) {
		return null;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * ID attribute of targeted list table
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	public function get_list_selector() {
		return '#the-list';
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_screen_id() {
		return $this->screen;
	}

	/**
	 * @since 2.0.3
	 * @return boolean
	 */
	public function is_current_screen() {
		$screen = get_current_screen();

		return $screen ? $screen->id === $this->get_screen_id() : false;
	}

	/**
	 * Set menu type
	 *
	 * @since 2.4.1
	 *
	 * @return AC_StorageModel
	 */
	public function set_menu_type( $menu_type ) {
		$this->menu_type = $menu_type;

		return $this;
	}

	/**
	 * @since 2.5
	 */
	public function get_menu_type() {
		return $this->menu_type;
	}

	/**
	 * @return array Meta keys
	 */
	public function get_meta() {
		return array();
	}

	/**
	 * Are column set by third party plugin
	 *
	 * @since 2.3.4
	 */
	public function is_using_php_export() {
		return apply_filters( 'ac/storage_model/is_using_php_export', false, $this );
	}

	/**
	 * @since 2.1.1
	 */
	public function get_post_type() {
		return isset( $this->post_type ) ? $this->post_type : false;
	}

	/**
	 * @since 2.3.4
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @since 2.3.4
	 */
	public function get_meta_type() {
		return $this->meta_type;
	}

	/**
	 * @since 2.0
	 * @return string Link
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'page' => $this->subpage ), admin_url( $this->page . '.php' ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_edit_link() {
		return apply_filters( 'ac/storage_model/edit_link', add_query_arg( array( 'cpac_key' => $this->key ), AC()->settings()->get_link( 'columns' ) ) );
	}

	// TODO: should these be in the AC_Columns?

	/**
	 * Get a single row from list table
	 *
	 * @since NEWVERSION
	 */
	public function get_single_row( $object_id ) {
		ob_start();
		$this->get_list_table()->single_row( $this->get_object_by_id( $object_id ) );

		return ob_get_clean();
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_list_table( $args = array() ) {

		$table = false;

		// WP Core tables
		if ( function_exists( '_get_list_table' ) ) {
			$table = _get_list_table( $this->table_classname, array( 'screen' => $this->get_screen_id() ) );
		}

		// Custom tables
		if ( ! $table && class_exists( $this->table_classname ) ) {
			$table = new $this->table_classname( $args );
		}

		return $table;
	}

	// TODO: should these be in the SM?

	/**
	 * @return AC_Settings
	 */
	public function settings() {
		if ( null === $this->settings ) {
			$this->settings = new AC_Settings( $this->key );
		}

		return $this->settings;
	}

	/**
	 * @return AC_Columns
	 */
	public function columns() {
		if ( null == $this->columns ) {
			$this->columns = new AC_Columns( $this->key );
		}

		return $this->columns;
	}

}