<?php

namespace AC\Service;

use AC\Column\Placeholder;
use AC\IntegrationRepository;
use AC\ListScreen;
use AC\PluginInformation;
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
		if ( ! function_exists( 'ACP' ) || ! ACP()->is_version_gte( 6 ) ) {
			// Placeholder columns
			foreach ( $this->repository->find_all() as $integration ) {
				if ( ! $integration->show_placeholder( $list_screen ) ) {
					continue;
				}

				$plugin_info = new PluginInformation( $integration->get_basename() );

				if ( $integration->is_plugin_active() && ! $plugin_info->is_active() ) {
					$column = new Placeholder();
					$column->set_integration( $integration );

					$list_screen->register_column_type( $column );
				}
			}
		}
	}

}