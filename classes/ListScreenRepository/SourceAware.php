<?php

namespace AC\ListScreenRepository;

use AC\Type\ListScreenId;

interface SourceAware
{

    public function get_source(ListScreenId $list_screen_id): string;

    public function has_source(ListScreenId $list_screen_id): bool;

}