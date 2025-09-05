<?php

declare(strict_types=1);

namespace AC\ListScreenRepository\Storage;

use AC\ListScreenRepository\Rules;

interface ListScreenRepositoryFactory
{

    public function create(
        string $path,
        bool $writable,
        ?Rules $rules = null,
        ?string $i18n_text_domain = null
    ): ListScreenRepository;

}