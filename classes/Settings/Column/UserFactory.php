<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class UserFactory implements SettingFactory
{

    private $user_link_factory;

    public function __construct(UserLinkFactory $user_link_factory)
    {
        $this->user_link_factory = $user_link_factory;
    }

    public function create(Config $config, Specification $specification = null): Column
    {
        return new User(
            (string)$config->get('display_author_as'),
            new SettingCollection([
                $this->user_link_factory->create($config),
            ]),
            $specification
        );
    }

}