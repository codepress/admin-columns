<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class FileLink extends BaseComponentFactory
{

    private const NAME = 'file_link_to';

    protected function get_label(Config $config): ?string
    {
        return __('Link to', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array([
                ''         => __('View file', 'codepress-admin-columns'),
                'download' => __('Download file', 'codepress-admin-columns'),
                'edit'     => __('Edit file', 'codepress-admin-columns'),
            ]),
            (string)$config->get(self::NAME, '')
        );
    }

}
