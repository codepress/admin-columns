<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Settings\GeneralOption;
use AC\Settings\GeneralOptionFactory;

class IntegrationStatus
{

    private GeneralOptionFactory $factory;

    public function __construct(GeneralOptionFactory $factory)
    {
        $this->factory = $factory;
    }

    private function get_storage(): GeneralOption
    {
        return $this->factory->create();
    }

    public function set_active(string $slug): void
    {
        $this->get_storage()->delete('integration_' . $slug);
    }

    public function set_inactive(string $slug): void
    {
        $this->get_storage()->save('integration_' . $slug, 'inactive');
    }

    private function get_cached_states(): array
    {
        static $states = null;

        if (null === $states) {
            $states = $this->get_storage()->all();
        }

        return $states;
    }

    public function is_active(string $slug): bool
    {
        $state = $this->get_cached_states()['integration_' . $slug] ?? null;

        return in_array(
            $state,
            ['active', null],
            true
        );
    }

}