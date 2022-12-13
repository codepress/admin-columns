<?php

namespace AC\Controller\Middleware;

use AC\Admin\Preference;
use AC\ListScreenRepository\Storage;
use AC\ListScreenTypes;
use AC\Middleware;
use AC\Request;
use AC\Type\ListScreenId;

class ListScreenAdmin implements Middleware {

	public const PARAM_LIST_ID = 'list_id';
	public const PARAM_LIST_KEY = 'list_key';

	private $storage;

	private $preference;

	private $is_network;

	public function __construct( Storage $storage, Preference\ListScreen $preference, bool $is_network = false ) {
		$this->storage = $storage;
		$this->preference = $preference;
		$this->is_network = $is_network;
	}

	public function handle( Request $request ) {
		$list_key = $request->get( 'list_screen' );

		// TODO
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

		if ( ! ListScreenId::is_valid_id( $list_id ) ) {
			$list_id = $this->preference->get_list_id( $list_key );
		}

		if ( ! ListScreenId::is_valid_id( $list_id ) || ! $this->storage->exists( new ListScreenId( $list_id ) ) ) {
			$list_screens = $this->storage->find_all_by_key( $list_key );

			$list_id = $list_screens->count() > 0
				? $list_screens->get_first()->get_id()->get_id()
				: null;
		}

		$request->get_parameters()->merge( [
			self::PARAM_LIST_ID  => $list_id,
			self::PARAM_LIST_KEY => $list_key,
		] );
	}

}