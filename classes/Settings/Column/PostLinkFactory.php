<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class PostLinkFactory implements SettingFactory
{

    private $relation;

    public function __construct(AC\Relation $relation)
    {
        $this->relation = $relation;
    }

    public function create(Config $config, Specification $specification = null): Column
    {
        return new PostLink(
            $config->get('post_link_to') ?: 'edit_post',
            $this->relation,
            $specification
        );
    }

}