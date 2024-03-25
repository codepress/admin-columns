<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

final class MediaLink extends Builder
{

    private const NAME = 'media_link_to';

    protected function get_label(Config $config): ?string
    {
        return __('Link to', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array([
                ''         => __('None'),
                'view'     => __('View', 'codepress-admin-columns'),
                'download' => __('Download', 'codepress-admin-columns'),
            ]),
            (string)$config->get(self::NAME)
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        return new FormatterCollection([
            new Formatter\Media\Link((string)$config->get(self::NAME)),
        ]);
    }

}