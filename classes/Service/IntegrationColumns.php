<?php

namespace AC\Service;

use AC\Column\Placeholder;
use AC\IntegrationRepository;
use AC\Registerable;
use AC\TableScreen;

final class IntegrationColumns implements Registerable
{

    private $repository;

    private $is_pro_active;

    public function __construct(IntegrationRepository $repository, bool $is_pro_active)
    {
        $this->repository = $repository;
        $this->is_pro_active = $is_pro_active;
    }

    public function register(): void
    {
        add_filter('ac/column_types', [$this, 'add_placeholder_column_types'], 1, 2);
    }

    public function add_placeholder_column_types(array $columns, TableScreen $table_screen): array
    {
        if ($this->is_pro_active) {
            return $columns;
        }

        foreach ($this->repository->find_all_by_active_plugins() as $integration) {
            if ( ! $integration->show_placeholder($table_screen)) {
                continue;
            }

            $columns[] = (new Placeholder())->set_integration($integration);
        }

        return $columns;
    }

}