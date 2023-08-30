<?php

namespace AC\ListScreenRepository;

use AC\Type\ListScreenId;
use ACP\Storage\Directory;
use SplFileInfo;

interface SourceAware
{

    public function get_source_directory(): Directory;

    public function has_source_directory(): bool;

    public function get_source_file(ListScreenId $id): SplFileInfo;

    public function has_source_file(ListScreenId $id): bool;

}