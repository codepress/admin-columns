<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Post;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Builder;
use AC\Setting\ComponentFactory\ImageSize;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\FileSize;

class FeaturedImageDisplay extends Builder
{

    public const NAME = 'featured_image_display';

    private ImageSize $image_size;

    public function __construct(ImageSize $image_size)
    {
        $this->image_size = $image_size;
    }

    protected function get_label(Config $config): string
    {
        return __('Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array([
                'image'    => __('Image'),
                'filesize' => __('Filesize', 'codepress-admin-columns'),
            ]),
            $config->get(self::NAME, 'image')
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->image_size->create(
                    $config,
                    StringComparisonSpecification::equal('image')
                ),
            ])
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        if ('filesize' === $this->get_input($config)->get_value()) {
            $formatters->add(new FileSize());
        }
    }

}