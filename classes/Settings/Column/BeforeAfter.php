<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\ArrayImmutable;
use AC\Setting\Base;
use AC\Setting\Input;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use ACP\Expression\Specification;

// TODO David good use case to think about BaseFormatter/ Runtime extension
class BeforeAfter extends Recursive
{

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = 'before_after';
        $this->label = __('Display Options', 'codepress-admin-columns');
        $this->input = new Input\Custom('empty');

        parent::__construct($column, $specification);
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
                Input\Open::create_text()
            ),
            new Base\Setting(
                'after',
                __('After', 'codepress-admin-columns'),
                '',
                Input\Open::create_text()
            ),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        if (ac_helper()->string->is_empty($value->get_value())) {
            return $value;
        }

        $before = $options->get('before');
        $after = $options->get('after');

        if ($before || $after) {
            return $value->with_value($before . $value->get_value() . $after);
        }

        return $value;
    }

}