<?php

abstract class AC_Settings_Column_Meta extends AC_Settings_Column {

	/**
	 * @var string
	 */
	private $field;

	abstract protected function get_meta_keys();

	protected function define_options() {
		return array( 'field' );
	}

	/**
	 * @return AC_Settings_Form_Element_Select
	 */
	protected function get_setting_field() {
		$setting = $this->create_element( 'select', 'field' )
		              ->set_options( $this->group_keys( $this->get_cached_keys() ) )
		              ->set_no_result( __( 'No fields available.', 'codepress-admin-columns' ) );

		return $setting;
	}

	/**
	 * @return array|false
	 */
	protected function get_cached_keys() {
		$keys = $this->get_cache();

		if ( ! $keys ) {
			$keys = $this->get_meta_keys();

			$this->set_cache( $keys );
		}

		return $keys;
	}

	/**
	 * @return string
	 */
	protected function get_cache_key() {
		return $this->column->get_list_screen()->get_storage_key();
	}

	protected function get_cache_group() {
		return 'ac_settings_meta';
	}

	/**
	 * @return AC_View
	 */
	public function create_view() {
		$view = new AC_View( array(
			'label'   => __( 'Field', 'codepress-admin-columns' ),
			'setting' => $this->get_setting_field(),
		) );

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
	 * @return bool
	 */
	public function set_field( $field ) {
		$this->field = $field;

		return true;
	}

	/**
	 * Get temp cache
	 */
	private function get_cache() {
		wp_cache_get( $this->get_cache_key(), $this->get_cache_group() );
	}

	/**
	 * @param array $data
	 * @param int   $expire Seconds
	 */
	private function set_cache( $data, $expire = 15 ) {
		wp_cache_add( $this->get_cache_key(), $data, $this->get_cache_group(), $expire );
	}

	private function group_keys( $keys ) {
		if ( ! $keys ) {
			return array();
		}

		$options = array(
			'hidden' => array(
				'title'   => __( 'Hidden Custom Fields', 'codepress-admin-columns' ),
				'options' => array(),
			),
			'public' => array(
				'title'   => __( 'Custom Fields', 'codepress-admin-columns' ),
				'options' => array(),
			),
		);

		foreach ( $keys as $field ) {
			$group = 0 === strpos( $field[0], '_' ) ? 'hidden' : 'public';

			$options[ $group ]['options'][ $field ] = $field;
		}

		krsort( $options ); // public first

		if ( empty( $options['hidden']['options'] ) ) {
			$options = $options['public']['options'];
		}

		return $options;
	}

}