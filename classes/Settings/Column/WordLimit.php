<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\Number;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class WordLimit extends Settings\Setting implements Formatter
{

    private $word_limit;

    public function __construct(?int $word_limit = 20, Specification $conditions = null)
    {
        parent::__construct(
            Number::create_single_step(
                'excerpt_length',
                0,
                null,
                $word_limit,
                null,
                null,
                __('Words', 'codepress-admin-columns')
            ),
            __('Word Limit', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of words', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            $conditions
        );

        $this->word_limit = $word_limit;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->string->trim_words(
                (string)$value->get_value(),
                $this->word_limit
            )
        );
    }

    // TODO implement usage
    public function get_word_limit(): ?int
    {
        return $this->word_limit;
    }



    // TODO
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