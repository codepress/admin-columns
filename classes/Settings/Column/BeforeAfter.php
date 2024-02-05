<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\Config;
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
            __('Display Options', 'codepress-admin-columns'),
            '',
            null,
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
                __('Before', 'codepress-admin-columns'),
                '',
                // TODO
                new Component\Input\Open('before', $this->before)
            ),
            new AC\Settings\Setting(
                __('After', 'codepress-admin-columns'),
                '',
                // TODO
                new Component\Input\Open('after', $this->after)
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