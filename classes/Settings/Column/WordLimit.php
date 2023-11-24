<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;

class WordLimit extends Settings\Column
{

    //implements Settings\FormatValue {

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'word_limit';
        $this->label = __('Word Limit', 'codepress-admin-columns');
        $this->input = new AC\Setting\Input\Number('20', 0);

        parent::__construct($column, $conditions);
    }

    //	/**
    //	 * @var int
    //	 */
    //	private $excerpt_length;
    //
    //	protected function set_name() {
    //		$this->name = 'word_limit';
    //	}
    //
    //	protected function define_options() {
    //		return [
    //			'excerpt_length' => 20,
    //		];
    //	}
    //
    //	public function create_view() {
    //		$setting = $this->create_element( 'number' )
    //		                ->set_attributes( [
    //			                'min'  => 0,
    //			                'step' => 1,
    //		                ] );
    //
    //		$view = new View( [
    //			'label'   => __( 'Word Limit', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
    //			'setting' => $setting,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_excerpt_length() {
    //		return $this->excerpt_length;
    //	}
    //
    //	/**
    //	 * @param int $excerpt_length
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_excerpt_length( $excerpt_length ) {
    //		$this->excerpt_length = $excerpt_length;
    //
    //		return true;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		$values = [];
    //
    //		foreach ( (array) $value as $_string ) {
    //			$values[] = ac_helper()->string->trim_words( $_string, $this->get_excerpt_length() );
    //		}
    //
    //		return ac_helper()->html->implode( $values );
    //	}

}