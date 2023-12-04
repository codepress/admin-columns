<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\SettingTrait;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\Specification;

class CharacterLimit extends Settings\Column implements AC\Setting\Formatter
{

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'character_limit';
        $this->label = __('Character Limit', 'codepress-admin-columns');
        $this->description = __('Maximum number of characters', 'codepress-admin-columns') . '<em>' .
                             __(
                                 'Leave empty for no limit',
                                 'codepress-admin-columns'
                             ) . '</em>';
        $this->input = AC\Setting\Input\Number::create_single_step(
            0,
            null,
            20,
            null,
            'tiny',
            __('Characters', 'codepress-admin-columns')
        );

        parent::__construct($column, $conditions);
    }

    public function format(Value $value, array $options): Value
    {
        return $value->with_value(
            ac_helper()->string->trim_characters($value->get_value(), $options[$this->name] ?? 20)
        );
    }

}