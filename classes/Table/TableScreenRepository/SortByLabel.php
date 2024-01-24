<?php

declare(strict_types=1);

namespace AC\Table\TableScreenRepository;

use AC\Table\TableScreenCollection;
use AC\TableScreen;

class SortByLabel implements Sort
{

    public function sort(TableScreenCollection $collection): TableScreenCollection
    {
        $screens = iterator_to_array($collection);

        uasort($screens, [$this, 'sort_by_label']);

        return new TableScreenCollection($screens);
    }

    public function sort_by_label(TableScreen $a, TableScreen $b): int
    {
        return strnatcasecmp((string)$a->get_labels(), (string)$b->get_labels());
    }

}