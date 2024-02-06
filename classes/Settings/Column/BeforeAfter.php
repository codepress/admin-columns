<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

class BeforeAfter extends AC\Settings\Setting implements AC\Setting\Recursive, AC\Setting\Formatter
{

    private $before;

    private $after;

    public function __construct(
        string $before = null,
        string $after = null,
        Specification $conditions = null
    ) {
        // TODO input?
        parent::__construct(
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

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new AC\Settings\Setting(
                new Component\Input\Open('before', $this->before),
                __('Before', 'codepress-admin-columns')
            ),
            new AC\Settings\Setting(
                new Component\Input\Open('after', $this->after),
                __('After', 'codepress-admin-columns')
            ),
        ]);
    }

    public function format(Value $value): Value
    {
        if (is_string($value->get_value()) && ac_helper()->string->is_not_empty($value->get_value())) {
            return $value->with_value(
                $this->before .
                $value->get_value() .
                $this->after
            );
        }

        return $value;
    }

    public function get_before(): ?string
    {
        return $this->before;
    }

    public function get_after(): ?string
    {
        return $this->after;
    }

}