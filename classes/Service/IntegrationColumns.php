<?php

namespace AC\Service;

use AC\Column\Placeholder;
use AC\IntegrationRepository;
use AC\ListScreen;
use AC\Registerable;

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
        add_action('ac/column_types', [$this, 'register_integration_columns'], 1);
    }

    public function register_integration_columns(ListScreen $list_screen): void
    {
        if ( ! $this->is_pro_active) {
            foreach ($this->repository->find_all_by_active_plugins() as $integration) {
                if ( ! $integration->show_placeholder($list_screen)) {
                    continue;
                }

                $column = new Placeholder();
                $column->set_integration($integration);

                // TODO use TableScreen::set_column_type()
//                $list_screen->register_column_type($column);
            }
        }
    }

}