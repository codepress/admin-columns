<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Input\Number;
use AC\Setting\Type\Value;
use AC\Settings;

class CharacterLimit extends Settings\Column implements AC\Setting\Formatter
{

    public function __construct(Specification $conditions = null, ?int $default = 20)
    {
        parent::__construct(
            'character_limit',
            __('Character Limit', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of characters', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            Number::create_single_step(
                0,
                null,
                $default,
                null,
                'tiny',
                __('Characters', 'codepress-admin-columns')
            ),
            $conditions
        );
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