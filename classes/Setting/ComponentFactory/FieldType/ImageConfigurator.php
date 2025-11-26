<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeBuilder;
use AC\Value\Formatter;

class ImageConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'image';

    private ComponentFactory\ImageSize $image_size;

    public function __construct(
        ComponentFactory\ImageSize $image_size
    ) {
        $this->image_size = $image_size;
    }

    public function configure(FieldTypeBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Image', 'codepress-admin-columns'), 'basic')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $formatters->add(new Formatter\IdCollectionFromArrayOrString());
                }
            )->add_child_component(
                $this->image_size,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}