<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class AttachmentsFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Attachments(
            $config->get('attachment_display') ?: 'thumbnail',
            new SettingCollection([
                (new ImageFactory())->create($config, StringComparisonSpecification::equal('thumbnail')),
            ]),
            $specification
        );
    }

}