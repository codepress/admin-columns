<?php

namespace AC\Admin\Asset;

use AC;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\ListScreen;
use AC\UnitializedListScreens;

class Columns extends Script {

	/**
	 * @var ListScreen
	 */
	private $list_screen;

	/**
	 * @var UnitializedListScreens
	 */
	private $uninitialized;

	public function __construct( $handle, Location $location, UnitializedListScreens $uninitialized, ListScreen $list_screen = null ) {
		parent::__construct( $handle, $location, [
			'jquery',
			'dashboard',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'wp-pointer',
		] );

		$this->list_screen = $list_screen;
		$this->uninitialized = $uninitialized;
	}

	public function register() {
		parent::register();

		if ( null === $this->list_screen ) {
			return;
		}

		$params = [
			'_ajax_nonce'                => wp_create_nonce( AC\Ajax\Handler::NONCE_ACTION ),
			'list_screen'                => $this->list_screen->get_key(),
			'layout'                     => $this->list_screen->get_layout_id(),
			'original_columns'           => [],
			'uninitialized_list_screens' => [],
			'i18n'                       => [
				'clone'  => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
				'error'  => __( 'Invalid response.', 'codepress-admin-columns' ),
				'errors' => [
					'save_settings'  => __( 'There was an error during saving the column settings.', 'codepress-admin-columns' ),
					'loading_column' => __( 'The column could not be loaded because of an unknown error', 'codepress-admin-columns' ),
				],
			],
		];

		foreach ( $this->uninitialized->get_list_screens() as $list_screen ) {

			$key = $list_screen->get_key();

			$params['uninitialized_list_screens'][ $key ] = [
				'screen_link' => add_query_arg( [ 'save-default-headings' => '1', 'list_screen' => $key ], $list_screen->get_screen_link() ),
				'label'       => $list_screen->get_label(),
			];
		}

		wp_localize_script( 'ac-admin-page-columns', 'AC', $params );
	}

}