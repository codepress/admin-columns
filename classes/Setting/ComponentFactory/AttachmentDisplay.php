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

final class AttachmentDisplay implements ComponentFactory
{

    private $image_size;

    public function __construct(ImageSize $image_size)
    {
        $this->image_size = $image_size;
    }

    // TODO formatter
    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'attachment_display',
                    OptionCollection::from_array([
                        'thumbnail' => __('Thumbnails', 'codepress-admin-columns'),
                        'count'     => __('Count', 'codepress-admin-columns'),
                    ]),
                    $config->get('attachment_display') ?: 'thumbnail'
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        $this->image_size->create(
                            $config,
                            StringComparisonSpecification::equal('thumbnail')
                        ),
                    ])
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}