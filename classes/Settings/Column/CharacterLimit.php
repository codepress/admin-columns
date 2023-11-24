<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;

class CharacterLimit extends Settings\Column
{

    //implements Settings\FormatValue {

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'character_limit';
        $this->label = __('Character Limit', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Number::create_single_step(0);

        parent::__construct($column, $conditions);
    }

    //	/**
    //	 * @var int
    //	 */
    //	private $character_limit;
    //
    //	protected function define_options() {
    //		return [
    //			'character_limit' => 20,
    //		];
    //	}
    //
    //	public function create_view() {
    //		$word_limit = $this->create_element( 'number' )
    //		                   ->set_attribute( 'min', 0 )
    //		                   ->set_attribute( 'step', 1 );
    //
    //		$view = new View( [
    //			'label'   => __( 'Character Limit', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Maximum number of characters', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
    //			'setting' => $word_limit,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_character_limit() {
    //		return $this->character_limit;
    //	}
    //
    //	/**
    //	 * @param int $character_limit
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_character_limit( $character_limit ) {
    //		$this->character_limit = $character_limit;
    //
    //		return true;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		return ac_helper()->string->trim_characters( $value, $this->get_character_limit() );
    //	}

}