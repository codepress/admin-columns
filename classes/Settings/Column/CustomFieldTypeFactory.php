<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class CustomFieldTypeFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new CustomFieldType(
            $config->get('field_type') ?: '',
            new SettingCollection([
                // TODO specification
                StringLimitFactory::create($config, StringComparisonSpecification::equal(CustomFieldType::TYPE_TEXT))
            ])
        );
    }

}