<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class CustomFieldFactory implements SettingFactory
{

    private $custom_field_type_factory;

    public function __construct(CustomFieldTypeFactory $custom_field_type_factory)
    {
        $this->custom_field_type_factory = $custom_field_type_factory;
    }

    public function create(Config $config, Specification $specification = null): Column
    {
        return new CustomField(
            $config->get('field') ?: '',
            new SettingCollection([
                $this->custom_field_type_factory->create($config),
            ])
        );
    }

}