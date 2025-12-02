<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;

interface FieldTypeConfigurator
{

    public function configure(FieldTypeFactoryBuilder $builder): void;

}