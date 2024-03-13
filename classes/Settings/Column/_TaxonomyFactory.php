<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class TaxonomyFactory implements SettingFactory
{

    private $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Taxonomy(
            $config->get('taxonomy') ?: '',
            $this->post_type,
            $specification
        );
    }

}