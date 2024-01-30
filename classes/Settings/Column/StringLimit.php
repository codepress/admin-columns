<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;
use AC\Setting;

class StringLimit extends Settings\Column implements Setting\Recursive, Setting\Formatter
{

    private $config;

    // TODO inject the `child settings`
    public function __construct(Config $config = null, Specification $conditions = null)
    {
        parent::__construct(
            'string_limit',
            __('Text Limit', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'string_limit',
                OptionCollection::from_array(
                    [
                        ''                => __('No limit', 'codepress-admin-columns'),
                        'character_limit' => __('Character Limit', 'codepress-admin-columns'),
                        'word_limit'      => __('Word Limit', 'codepress-admin-columns'),
                    ]
                ),
                $this->get_string_limiter()
            ),
            $conditions
        );

        $this->config = $config;
    }

    private function get_string_limiter():string
    {
        return $this->config && $this->config->has('string_limit')
            ? $this->config->get('string_limit')
            : 'word_limit';
    }

    public function is_parent(): bool
    {

        // TODO what is parent?
        return false;
    }

    public function format(Value $value): Value
    {
        $settings = $this->get_children();
    }

    public function get_children(): SettingCollection
    {
        // TODO test formatter
        return new SettingCollection([
            new Settings\Column\CharacterLimit(
            // TODO do we pass just the $char_limit and $word_limit or the whole Config
                $this->config && $this->config->has('character_limit') ? (int)$this->config->get('character_limit') : null,
                StringComparisonSpecification::equal('character_limit')
            ),
            new Settings\Column\WordLimit(
                $this->config && $this->config->has('word_limit') ? (int)$this->config->get('word_limit') : null,
                StringComparisonSpecification::equal('word_limit')
            ),
        ]);
    }


    // TODO
    //	/**
    //	 * @var string
    //	 */
    //	private $string_limit;
    //
    //	protected function define_options() {
    //		return [ 'string_limit' => 'word_limit' ];
    //	}
    //
    //	public function create_view() {
    //		$setting = $this->create_element( 'select' )
    //		                ->set_attribute( 'data-refresh', 'column' )
    //		                ->set_options( $this->get_limit_options() );
    //
    //		$view = new View( [
    //			'label'   => __( 'Text Limit', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Limit text to a certain number of characters or words', 'codepress-admin-columns' ),
    //			'setting' => $setting,
    //		] );
    //
    //		return $view;
    //	}
    //
    //	private function get_limit_options() {
    //		$options = [
    //			''                => __( 'No limit', 'codepress-admin-columns' ),
    //			'character_limit' => __( 'Character Limit', 'codepress-admin-columns' ),
    //			'word_limit'      => __( 'Word Limit', 'codepress-admin-columns' ),
    //		];
    //
    //		return $options;
    //	}
    //
    //	public function get_dependent_settings() {
    //		$setting = [];
    //
    //		switch ( $this->get_string_limit() ) {
    //
    //			case 'character_limit' :
    //				$setting[] = new Settings\Column\CharacterLimit( $this->column );
    //
    //				break;
    //			case 'word_limit' :
    //				$setting[] = new Settings\Column\WordLimit( $this->column );
    //
    //				break;
    //		}
    //
    //		return $setting;
    //	}
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_string_limit() {
    //		return $this->string_limit;
    //	}
    //
    //	/**
    //	 * @param string $string_limit
    //	 *
    //	 * @return true
    //	 */
    //	public function set_string_limit( $string_limit ) {
    //		$this->string_limit = $string_limit;
    //
    //		return true;
    //	}

}