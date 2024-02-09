<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\Component\Input\Open;
use AC\Setting\ComponentCollection;
use AC\Setting\Type\Value;
use AC\Settings\Setting;

// TODO component?
class BeforeAfter extends Setting implements AC\Setting\Recursive, AC\Setting\Formatter
{

    private $before;

    private $after;

    public function __construct(
        string $before = null,
        string $after = null,
        Specification $conditions = null
    ) {
        parent::__construct(
        // TODO input?
            new Component\Input\Custom('width'),
            __('Display Options', 'codepress-admin-columns'),
            $conditions
        );

        $this->before = $before;
        $this->after = $after;
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): ComponentCollection
    {
        return new ComponentCollection([
            new Setting(
                new Open('before', null, $this->before),
                __('Before', 'codepress-admin-columns')
            ),
            new Setting(
                new Open('after', null, $this->after),
                __('After', 'codepress-admin-columns')
            ),
        ]);
    }

    public function format(Value $value): Value
    {
        if (is_scalar($value->get_value()) && ac_helper()->string->is_not_empty($value->get_value())) {
            return $value->with_value(
                $this->before .
                $value->get_value() .
                $this->after
            );
        }

        return $value;
    }

}