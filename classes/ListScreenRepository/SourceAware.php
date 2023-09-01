<?php

namespace AC\ListScreenRepository;

use AC\Type\ListScreenId;

interface SourceAware
{

    public function get_source(ListScreenId $id): string;

    public function has_source(ListScreenId $id): bool;

}