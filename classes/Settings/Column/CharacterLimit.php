<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component\Input\Number;
use AC\Setting\Type\Value;
use AC\Settings;

class CharacterLimit extends Settings\Control implements AC\Setting\Formatter
{

    private $limit;

    public function __construct(?int $limit = 20, Specification $conditions = null)
    {
        parent::__construct(
            Number::create_single_step(
                'character_limit',
                0,
                null,
                $limit,
                null,
                null,
                __('Characters', 'codepress-admin-columns')
            ),
            __('Character Limit', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of characters', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            $conditions
        );
        $this->limit = $limit;
    }

    public function format(Value $value): Value
    {
        if ( ! $this->limit) {
            return $value;
        }

        return $value->with_value(
            ac_helper()->string->trim_characters(
                (string)$value->get_value(),
                $this->limit
            )
        );
    }

}