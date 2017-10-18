<?php

class AC_Settings_Column_StringLimit extends AC_Settings_Column {

	/**
	 * @var string
	 */
	private $string_limit;

	protected function define_options() {
		return array( 'string_limit' );
	}

	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_attribute( 'data-refresh', 'column' )
		                ->set_options( $this->get_limit_options() );

		$view = new AC_View( array(
			'label'   => __( 'Maximum Length', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	private function get_limit_options() {
		$options = array(
			''                => __( 'No Maximum Length', 'codepress-admin-columns' ),
			'character_limit' => __( 'Limit on Characters', 'codepress-admin-columns' ),
			'word_limit'      => __( 'Limit on Words', 'codepress-admin-columns' ),
		);

		return $options;
	}

	public function get_dependent_settings() {
		$setting = array();

		switch ( $this->get_string_limit() ) {
			case 'character_limit' :
				$setting[] = new AC_Settings_Column_CharacterLimit( $this->column );
				break;
			case 'word_limit' :
				$setting[] = new AC_Settings_Column_WordLimit( $this->column );
				break;
		}

		return $setting;
	}

	/**
	 * @return string
	 */
	public function get_string_limit() {
		return $this->string_limit;
	}

	/**
	 * @param string $string_limit
	 *
	 * @return true
	 */
	public function set_string_limit( $string_limit ) {
		$this->string_limit = $string_limit;

		return true;
	}

}