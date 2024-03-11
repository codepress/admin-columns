<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

final class AttachmentDisplay implements ComponentFactory
{

    private const NAME = 'attachment_display';

    private const OPTION_THUMBNAIL = 'thumbnail';
    private const OPTION_COUNT = 'count';

    private $image_size_factory;

    private $media_link_factory;

    public function __construct(
        ImageSize $image_size_factory,
        MediaLink $media_link_factory
    ) {
        $this->image_size_factory = $image_size_factory;
        $this->media_link_factory = $media_link_factory;
    }

    // TODO formatter
    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME, 'thumbnail');

        $builder = (new ComponentBuilder())
            ->set_label(__('Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array([
                        self::OPTION_THUMBNAIL => __('Thumbnails', 'codepress-admin-columns'),
                        self::OPTION_COUNT     => __('Count', 'codepress-admin-columns'),
                    ]),
                    $value
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        $this->image_size_factory->create(
                            $config,
                            StringComparisonSpecification::equal(self::OPTION_THUMBNAIL)
                        ),
                        $this->media_link_factory->create(
                            $config,
                            StringComparisonSpecification::equal(self::OPTION_THUMBNAIL)
                        ),
                    ])
                )
            )
            ->set_formatter($this->get_formatter($value));

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function get_formatter(string $value): Formatter
    {
        if ($value === self::OPTION_COUNT) {
            return new Formatter\Count();
        }

        return new Formatter\Aggregate(
        // TODO David image size formatter?
        // TODO David get formatter, respecting the factory conditions
        );
        // TODO David implement aggregate for other
    }

}