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
use AC\Value\Formatter\DottedPassword;

final class Password implements ComponentFactory
{

    private const NAME = 'password';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME, '');

        $builder = (new ComponentBuilder())
            ->set_label(__('Display format', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'password',
                    OptionCollection::from_array([
                        ''     => __('Password', 'codepress-admin-column'),
                        'text' => __('Plain text', 'codepress-admin-column'),
                    ]),
                    $value
                )
            );

        if ($config->get('password') === '') {
            $builder->set_formatter(new DottedPassword());
        }

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}