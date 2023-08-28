<?php

namespace AC\ListScreenRepository;

use AC\Type\ListScreenId;
use ACP\Storage\Directory;
use SplFileInfo;

interface SourceAware
{

    public function get_source(): Directory;

    public function has_source(): bool;

    public function get_file_source(ListScreenId $id): SplFileInfo;

    public function has_file_source(ListScreenId $id): bool;

}