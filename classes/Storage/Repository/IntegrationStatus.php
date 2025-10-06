<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Settings\GeneralOption;

class IntegrationStatus
{

    private GeneralOption $storage;

    public function __construct(GeneralOption $storage)
    {
        $this->storage = $storage;
    }

    public function set_active(string $slug): void
    {
        $this->storage->delete('integration_' . $slug);
    }

    public function set_inactive(string $slug): void
    {
        $this->storage->save('integration_' . $slug, 'inactive');
    }

    public function is_active(string $slug): bool
    {
        $state = $this->storage->get('integration_' . $slug) ?? null;

        return in_array(
            $state,
            ['active', null],
            true
        );
    }

}