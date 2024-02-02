<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings;

// TODO Child collection and constructor params
class AttachmentDisplay extends Settings\Column\Recursive
{

    use AC\Setting\RecursiveFormatterTrait;

    public function __construct(string $attachment_type, AC\Expression\Specification $specification)
    {
        parent::__construct(
            __('Display', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'attachment_display',
                OptionCollection::from_array([
                    'thumbnail' => __('Thumbnails', 'codepress-admin-columns'),
                    'count'     => __('Count', 'codepress-admin-columns'),
                ]),
                $attachment_type ?: 'thumbnail'
            ),
            $specification
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Images(StringComparisonSpecification::equal('thumbnail')),
        ]);
    }

    public function format(Value $value): Value
    {
        // TODO
        return $value;

        return 'count' === $options->get($this->name)
            ? $value->with_value(count($value->get_value()))
            : parent::format($value, $options);
    }

}