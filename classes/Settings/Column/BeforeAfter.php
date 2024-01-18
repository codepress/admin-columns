<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Input;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;

class BeforeAfter extends AC\Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    private $default_before;

    private $default_after;

    public function __construct(
        Specification $conditions = null,
        string $default_before = null,
        string $default_after = null
    ) {
        // TODO input?
        parent::__construct('before_after', __('Display Options', 'codepress-admin-columns'), '', null, $conditions);

        $this->default_before = $default_before;
        $this->default_after = $default_after;
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new AC\Settings\Column(
                'before',
                __('Before', 'codepress-admin-columns'),
                '',
                Input\Open::create_text($this->default_before)
            ),
            new AC\Settings\Column(
                'after',
                __('After', 'codepress-admin-columns'),
                '',
                Input\Open::create_text($this->default_after)
            ),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        if (is_string($value->get_value()) && ac_helper()->string->is_not_empty($value->get_value())) {
            return $value->with_value(
                $options->get('before') .
                $value->get_value() .
                $options->get('after')
            );
        }

        return $value;
    }

}