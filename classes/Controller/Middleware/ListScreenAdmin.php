<?php

namespace AC\Controller\Middleware;

use AC\ListScreenRepository\Filter;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Middleware;
use AC\Preferences;
use AC\Request;
use AC\Type\ListScreenId;

class ListScreenAdmin implements Middleware {

	const PARAM_LIST_ID = 'list_id';
	const PARAM_LIST_KEY = 'list_key';

	/** @var Storage */
	private $storage;

	/** @var Preferences\AdminListScreen */
	private $preference;

	/** @var bool */
	private $is_network;

	public function __construct( Storage $storage, Preferences\AdminListScreen $preference, $is_network = false ) {
		$this->storage = $storage;
		$this->preference = $preference;
		$this->is_network = (bool) $is_network;
	}

	public function handle( Request $request ) {
		$list_key = $request->get( 'list_screen' );

		if ( ! $list_key ) {
			$list_key = $this->preference->get_last_visited_list_key();
		}

		if ( ! $list_key || ! ListScreenTypes::instance()->get_list_screen_by_key( $list_key, $this->is_network ) ) {
			$args = $this->is_network
				? [ 'network_only' => true ]
				: [ 'site_only' => true ];

			$list_key = current( ListScreenTypes::instance()->get_list_screens( $args ) )->get_key();
		}

		if ( ! $list_key ) {
			return;
		}

		$list_id = $request->get( 'layout_id' );

		if ( ! $list_id ) {
			$list_id = $this->preference->get_list_id( $list_key );
		}

		if ( ! $list_id || ! ListScreenId::is_valid_id( $list_id ) || ! $this->storage->exists( new ListScreenId( $list_id ) ) ) {

			$args = [
				Storage::KEY => $list_key,
			];

			$args[ Storage::ARG_FILTER ][] = $this->is_network
				? new Filter\Network()
				: new Filter\ExcludeNetwork();

			$list_screens = $this->storage->find_all( $args );

			if ( $list_screens->count() > 0 ) {
				$list_id = $list_screens->get_first()->get_id()->get_id();
			}
		}

		$request->get_parameters()->merge( [
			self::PARAM_LIST_ID  => $list_id,
			self::PARAM_LIST_KEY => $list_key,
		] );
	}

}