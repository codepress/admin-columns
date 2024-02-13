<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class PostLinkFactory implements SettingFactory
{

    private $relation;

    public function __construct(AC\Relation $relation = null)
    {
        $this->relation = $relation;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new PostLink(
            (string)$config->get('post_link_to'),
            $this->relation,
            $specification
        );
    }

}