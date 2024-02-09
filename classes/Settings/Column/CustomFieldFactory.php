<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\MetaType;
use AC\Setting\Config;
use AC\Setting\ComponentCollection;
use AC\Settings\Component;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class CustomFieldFactory implements SettingFactory
{

    private $meta_type;

    private $custom_field_type_factory;

    public function __construct(MetaType $meta_type, CustomFieldTypeFactory $custom_field_type_factory)
    {
        $this->meta_type = $meta_type;
        $this->custom_field_type_factory = $custom_field_type_factory;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new CustomField(
            $config->get('field') ?: '',
            $this->meta_type,
            new ComponentCollection([
                $this->custom_field_type_factory->create($config),
            ])
        );
    }

}