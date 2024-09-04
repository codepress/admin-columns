<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Type\TableScreenContext;

final class CustomFieldFactory
{

    public function create(TableScreenContext $table_screen_context): CustomField
    {
        return new CustomField(
            $table_screen_context
        );
    }

}