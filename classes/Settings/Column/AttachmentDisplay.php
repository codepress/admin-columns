<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Option;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\StringComparisonSpecification;

class AttachmentDisplay extends Settings\Column\Recursive
{

    use AC\Setting\SettingTrait;
    use AC\Setting\RecursiveFormatterTrait {
    }

    public function __construct(AC\Column $column)
    {
        $this->name = 'attachment_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            new OptionCollection([
                new Option(__('Thumbnails', 'codepress-admin-columns'), 'thumbnail'),
                new Option(__('Count', 'codepress-admin-columns'), 'count'),
            ])
            ,
            'thumbnail'
        );

        parent::__construct($column);
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Images($this->column, StringComparisonSpecification::equal('thumbnail')),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return 'count' === $options->get($this->name)
            ? $value->with_value(count($value->get_value()))
            : parent::format($value, $options);
    }

}