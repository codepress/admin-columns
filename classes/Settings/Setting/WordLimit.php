<?php

class AC_Settings_Setting_WordLimit extends AC_Settings_SettingAbstract
	implements AC_Settings_FormatInterface {

	/**
	 * @var int
	 */
	private $excerpt_length = 20;

	protected function set_name() {
		$this->name = 'word_limit';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'excerpt_length' );
	}

	public function create_view() {
		$setting = $this->create_element( 'number' )
		                ->set_attributes( array(
			                'min'         => 0,
			                'step'        => 1,

			                // TODO: needs a get default method
			                'placeholder' => 20,
		                ) );

		$view = new AC_View( array(
			'label'   => __( 'Word Limit', 'codepress-admin-columns' ),
			'tooltip' => __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_excerpt_length() {
		return $this->excerpt_length;
	}

	/**
	 * @param int $excerpt_length
	 *
	 * @return $this
	 */
	public function set_excerpt_length( $excerpt_length ) {
		$this->excerpt_length = $excerpt_length;

		return $this;
	}

	public function format( $string ) {
		return ac_helper()->string->trim_words( $string, $this->get_excerpt_length() );
	}

}