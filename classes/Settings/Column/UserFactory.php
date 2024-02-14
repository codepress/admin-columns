<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class UserFactory implements SettingFactory
{

    private $user_link_factory;

    public function __construct(UserLinkFactory $user_link_factory)
    {
        $this->user_link_factory = $user_link_factory;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new User(
            (string)$config->get('display_author_as'),
            new ComponentCollection([
                $this->user_link_factory->create($config),
            ]),
            $specification
        );
    }

}