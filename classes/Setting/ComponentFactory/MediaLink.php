<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

final class MediaLink implements ComponentFactory
{

    private const NAME = 'media_link_to';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME);

        $builder = (new ComponentBuilder())
            ->set_label(
                __('Link To', 'codepress-admin-columns')
            )
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array([
                        ''         => __('None'),
                        'view'     => __('View', 'codepress-admin-columns'),
                        'download' => __('Download', 'codepress-admin-columns'),
                    ]),
                    $value
                )
            )
            ->set_formatter(
                new Formatter\Media\Link($value)
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}