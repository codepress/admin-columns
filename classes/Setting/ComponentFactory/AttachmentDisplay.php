<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

final class AttachmentDisplay extends Builder
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

    protected function get_label(Config $config): ?string
    {
        return __('Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array([
                self::OPTION_THUMBNAIL => __('Thumbnails', 'codepress-admin-columns'),
                self::OPTION_COUNT     => __('Count', 'codepress-admin-columns'),
            ]),
            (string)$config->get(self::NAME, 'thumbnail')
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
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
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(new Formatter\Post\Attachments());

        if ($config->get(self::NAME) === self::OPTION_COUNT) {
            $formatters->add(new Formatter\Count());
        }

        return parent::get_formatters($config, $formatters);
    }

}