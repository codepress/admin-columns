<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\ComponentCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class StringLimit extends Settings\Setting implements Setting\Recursive, Setting\Formatter
{

    private $limiter;

    private $settings;

    public function __construct(string $limiter, ComponentCollection $settings, Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'string_limit',
                $this->create_option_collection($settings),
                $limiter
            ),
            __('Text Limit', 'codepress-admin-columns'),
            null,
            $conditions
        );

        $this->settings = $settings;
        $this->limiter = $limiter;
    }

    private function create_option_collection(ComponentCollection $settings): OptionCollection
    {
        $options = [
            '' => __('No limit', 'codepress-admin-columns'),
        ];

        foreach ($settings as $setting) {
            // TODO get_label
            $options[$setting->get_name()] = $setting->get_label();
        }

        return OptionCollection::from_array($options);
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function format(Value $value): Value
    {
        $settings = new ComponentCollection();

        foreach ($this->settings as $setting) {
            if ($setting->get_conditions()->is_satisfied_by($this->limiter)) {
                $settings->add($setting);
            }
        }

        return Aggregate::from_settings($settings)->format($value);
    }

    public function get_children(): ComponentCollection
    {
        return $this->settings;
    }

    //    public function get_children(): SettingCollection
    //    {

    // TODO test formatter
    //        return new SettingCollection([
    //            new Settings\Column\CharacterLimit(
    //            // TODO do we pass just the $char_limit and $word_limit or the whole Config
    //                $this->config && $this->config->has('character_limit') ? (int)$this->config->get('character_limit') : null,
    //                StringComparisonSpecification::equal('character_limit')
    //            ),
    //            new Settings\Column\WordLimit(
    //                $this->config && $this->config->has('word_limit') ? (int)$this->config->get('word_limit') : null,
    //                StringComparisonSpecification::equal('word_limit')
    //            ),
    //        ]);
    //    }

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