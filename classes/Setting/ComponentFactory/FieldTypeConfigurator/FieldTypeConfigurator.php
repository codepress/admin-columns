<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

interface FieldTypeConfigurator
{
    /**
     * The stored `field_type` value this configurator is responsible for. Also its key in the
     * ConfiguratorRegistry, so there is a single vocabulary. Never rename an existing one: the
     * value is persisted in the column settings.
     */
    public function get_type(): string;

    public function configure(FieldTypeFactoryBuilder $builder): void;

}
