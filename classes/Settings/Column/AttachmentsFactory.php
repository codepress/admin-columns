<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\ComponentCollection;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class AttachmentsFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Attachments(
            $config->get('attachment_display') ?: 'thumbnail',
            new ComponentCollection([
                (new ImageFactory())->create($config, StringComparisonSpecification::equal('thumbnail')),
            ]),
            $specification
        );
    }

}