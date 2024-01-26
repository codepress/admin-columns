<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\ArrayImmutable;
<<<<<<< HEAD
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Component\Type\Option;
=======
use AC\Setting\Input;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class AttachmentDisplay extends Settings\Column\Recursive
{

    use AC\Setting\RecursiveFormatterTrait;

    public function __construct()
    {
<<<<<<< HEAD
        $this->name = 'attachment_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
            new OptionCollection([
                new Option(__('Thumbnails', 'codepress-admin-columns'), 'thumbnail'),
                new Option(__('Count', 'codepress-admin-columns'), 'count'),
            ])
            ,
            'thumbnail'
=======
        parent::__construct(
            'attachment_display',
            __('Display', 'codepress-admin-columns'),
            '',
            Input\Option\Single::create_select(
                new OptionCollection([
                    new Option(__('Thumbnails', 'codepress-admin-columns'), 'thumbnail'),
                    new Option(__('Count', 'codepress-admin-columns'), 'count'),
                ]),
                'thumbnail'
            )
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Images(StringComparisonSpecification::equal('thumbnail')),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return 'count' === $options->get($this->name)
            ? $value->with_value(count($value->get_value()))
            : parent::format($value, $options);
    }

}