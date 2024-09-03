<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Type\ListKey;

final class CustomFieldFactory
{

    public function create(ListKey $list_key): CustomField
    {
        return new CustomField($list_key);
    }

}