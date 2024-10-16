<?php

namespace AC\ColumnRepository\Sort;

use AC\Preferences\SiteFactory;
use AC\Storage;
use AC\Type\ListScreenId;

class ManualOrder extends ColumnNames
{

    public function __construct(ListScreenId $list_id)
    {
        $storage = new Storage\Repository\UserColumnOrder(new SiteFactory());

        parent::__construct($storage->get($list_id));
    }

}