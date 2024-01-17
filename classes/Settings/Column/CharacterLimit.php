<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Input\Number;
use AC\Setting\SettingTrait;
use AC\Setting\Type\Value;
use AC\Settings;

class CharacterLimit extends Settings\Column implements AC\Setting\Formatter
{

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'character_limit';
        $this->label = __('Character Limit', 'codepress-admin-columns');
        $this->description = sprintf(
            '%s <em>%s</em>',
            __('Maximum number of characters', 'codepress-admin-columns'),
            __('Leave empty for no limit', 'codepress-admin-columns')
        );
        $this->input = Number::create_single_step(
            0,
            null,
            20,
            null,
            'tiny',
            __('Characters', 'codepress-admin-columns')
        );

        parent::__construct($column, $conditions);
    }

    public function format(Value $value, AC\Setting\ArrayImmutable $options): Value
    {
        return $value->with_value(
            ac_helper()->string->trim_characters(
                (string)$value->get_value(),
                $options->get($this->name) ?? 20
            )
        );
    }

}