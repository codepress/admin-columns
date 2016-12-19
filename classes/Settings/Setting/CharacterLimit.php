<?php

class AC_Settings_Setting_CharacterLimit extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	/**
	 * @var int
	 */
	private $character_limit;

	protected function define_options() {
		return array(
			'character_limit' => 20,
		);
	}

	public function create_view() {
		$word_limit = $this->create_element( 'number' )
		                   ->set_attribute( 'min', 0 )
		                   ->set_attribute( 'step', 1 );

		$view = new AC_View( array(
			'label'   => __( 'Character Limit', 'codepress-admin-columns' ),
			'tooltip' => __( 'Maximum number of characters', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
			'setting' => $word_limit,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_character_limit() {
		return $this->character_limit;
	}

	/**
	 * @param int $character_limit
	 *
	 * @return bool
	 */
	public function set_character_limit( $character_limit ) {
		$this->character_limit = $character_limit;

		return true;
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function format( $string ) {
		return ac_helper()->string->trim_characters( $string, $this->get_character_limit() );
	}

}