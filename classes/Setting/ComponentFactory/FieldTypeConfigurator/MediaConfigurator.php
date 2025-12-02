<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class MediaConfigurator implements FieldTypeConfigurator
{

    private const TYPE = 'library_id';

    private Setting\ComponentFactory\ImageSize $image_size;

    private Setting\ComponentFactory\MediaLink $media_link;

    public function __construct(
        Setting\ComponentFactory\ImageSize $image_size,
        Setting\ComponentFactory\MediaLink $media_link
    ) {
        $this->image_size = $image_size;
        $this->media_link = $media_link;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Media', 'codepress-admin-columns'), 'relational')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $formatters->add(new AC\Value\Formatter\IdCollectionFromArrayOrString());
                }
            )->add_child_component(
                $this->image_size,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_child_component(
                $this->media_link,
                StringComparisonSpecification::equal(self::TYPE)
            );
    }
}