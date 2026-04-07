<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Expression\StringComparisonSpecification;
use AC\Formatter\Collection\Separator;
use AC\Formatter\ImageToCollection;
use AC\Setting;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

class ImageConfigurator implements FieldTypeConfigurator
{

    public const TYPE = 'image';

    private ComponentFactory\ImageSize $image_size;

    private ComponentFactory\MediaLink $media_link;

    private ComponentFactory\NumberOfItems $number_of_items;

    public function __construct(
        ComponentFactory\ImageSize $image_size,
        ComponentFactory\MediaLink $media_link,
        ComponentFactory\NumberOfItems $number_of_items
    ) {
        $this->image_size = $image_size;
        $this->media_link = $media_link;
        $this->number_of_items = $number_of_items;
    }

    public function configure(FieldTypeFactoryBuilder $builder): void
    {
        $builder
            ->add_option(self::TYPE, __('Image', 'codepress-admin-columns'), 'basic')
            ->add_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $formatters->add(new ImageToCollection());
                }
            )->add_child_component(
                $this->image_size,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_child_component(
                $this->media_link,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_child_component(
                $this->number_of_items,
                StringComparisonSpecification::equal(self::TYPE)
            )->add_final_formatter(
                self::TYPE,
                function (Setting\Config $config, Setting\FormatterCollection $formatters) {
                    $formatters->add(new Separator('', (int)$config->get('number_of_items', 0)));
                }
            );
    }
}