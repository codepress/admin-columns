<?php

declare(strict_types=1);

namespace AC\Table\TableScreenRepository;

use AC\ListKeyCollection;
use AC\Table\TableScreenCollection;

class FilterByKey implements Filter
{

    private $list_keys;

    public function __construct(ListKeyCollection $list_keys)
    {
        $this->list_keys = $list_keys;
    }

    public function filter(TableScreenCollection $collection): TableScreenCollection
    {
        $table_screens = [];

        foreach ($collection as $table_screen) {
            if ($this->list_keys->contains($table_screen->get_key())) {
                $table_screens[] = $table_screen;
            }
        }

        return new TableScreenCollection($table_screens);
    }

}