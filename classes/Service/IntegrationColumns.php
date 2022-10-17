<?php

namespace AC\Service;

use AC\Column\Placeholder;
use AC\IntegrationRepository;
use AC\ListScreen;
use AC\Registrable;

final class IntegrationColumns implements Registrable {

	/**
	 * @var IntegrationRepository
	 */
	private $repository;

	public function __construct( IntegrationRepository $repository ) {
		$this->repository = $repository;
	}

	public function register() {
		add_action( 'ac/column_types', [ $this, 'register_integration_columns' ], 1 );
	}

	public function register_integration_columns( ListScreen $list_screen ) {
		if ( ! function_exists( 'ACP' ) ) {

			foreach ( $this->repository->find_all() as $integration ) {
				if ( ! $integration->show_placeholder( $list_screen ) ) {
					continue;
				}

				if ( $integration->is_plugin_active() ) {
					$column = new Placeholder();
					$column->set_integration( $integration );

					$list_screen->register_column_type( $column );
				}
			}
		}
	}

}