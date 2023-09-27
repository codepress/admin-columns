<?php

namespace AC\ListScreenRepository;

use AC\Type\ListScreenId;

interface SourceAware
{

    public function get_source(ListScreenId $list_screen_id): string;

    public function has_source(ListScreenId $list_screen_id): bool;

    // TODO David implement get_sources(); in order to make this cache-able and maybe even replace the two has functions
    // with a simpler lookup of e.g. a source collection?

}