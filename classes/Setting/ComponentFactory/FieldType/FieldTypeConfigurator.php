<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldType;

use AC\Setting\ComponentFactory\FieldTypeBuilder;

interface FieldTypeConfigurator
{

    public function configure(FieldTypeBuilder $builder): void;
}