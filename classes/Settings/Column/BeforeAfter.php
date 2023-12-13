<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Column;
use AC\Setting\ArrayImmutable;
use AC\Setting\Base;
use AC\Setting\Input;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use ACP\Expression\Specification;

class BeforeAfter extends AC\Settings\Column implements AC\Setting\Recursive, AC\Setting\Formatter
{

    private $default_before;

    private $default_after;

    public function __construct(
        Column $column,
        Specification $conditions = null,
        string $default_before = null,
        string $default_after = null
    ) {
        $this->name = 'before_after';
        $this->label = __('Display Options', 'codepress-admin-columns');
        $this->default_before = $default_before;
        $this->default_after = $default_after;

        parent::__construct($column, $conditions);
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Base\Setting(
                'before',
                __('Before', 'codepress-admin-columns'),
                '',
                Input\Open::create_text($this->default_before)
            ),
            new Base\Setting(
                'after',
                __('After', 'codepress-admin-columns'),
                '',
                Input\Open::create_text($this->default_after)
            ),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        if ( ! ac_helper()->string->is_empty($value->get_value())) {
            $value = $value->with_value(
                $options->get('before') .
                $value->get_value() .
                $options->get('after')
            );
        }

        return $value;
    }

}