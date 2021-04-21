<?php

namespace AC\Controller\Middleware;

use AC\ListScreenRepository\Filter;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\PermissionChecker;
use AC\Request;
use AC\Screen;
use AC\Table;
use AC\Type\ListScreenId;
use WP_Screen;

class ListScreenTable implements Middleware {

	const PARAM_LIST_ID = 'list_id';
	const PARAM_LIST_KEY = 'list_key';

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var WP_Screen
	 */
	private $wp_screen;

	/**
	 * @var Table\Preference
	 */
	private $preference;

	public function __construct( Storage $storage, WP_Screen $wp_screen, Table\Preference $preference ) {
		$this->storage = $storage;
		$this->wp_screen = $wp_screen;
		$this->preference = $preference;
	}

	/**
	 * @return string|null
	 */
	private function get_list_key_from_screen() {
		return ( new Screen() )->set_screen( $this->wp_screen )->get_list_screen();
	}

	/**
	 * Set the list_key and layout
	 *
	 * @param Request $request
	 */
	public function handle( Request $request ) {
		$list_key = $request->get( self::PARAM_LIST_KEY );

		if ( ! $list_key ) {
			$list_key = $this->get_list_key_from_screen();
		}

		if ( ! $list_key ) {
			return;
		}

		$list_id = $request->get( 'layout' );

		if ( ! ListScreenId::is_valid_id( $list_id ) ) {
			$list_id = $this->preference->get( $list_key );
		}

		if ( ! ListScreenId::is_valid_id( $list_id ) || ! $this->storage->exists( new ListScreenId( $list_id ) ) ) {

			$list_screens = $this->storage->find_all( [
				Storage::KEY        => $list_key,
				Storage::ARG_FILTER => [
					new Filter\Permission( new PermissionChecker() ),
				],
			] );

			$list_id = $list_screens->count() > 0
				? $list_screens->get_first()->get_id()->get_id()
				: null;
		}

		$request->get_parameters()->merge( [
			self::PARAM_LIST_KEY => $list_key,
			self::PARAM_LIST_ID  => $list_id,
		] );
	}

}