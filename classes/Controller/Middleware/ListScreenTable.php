<?php
declare( strict_types=1 );

namespace AC\Controller\Middleware;

use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\ListScreenPermissionTrait;
use AC\ListScreenRepository\Sort\ManualOrder;
use AC\ListScreenRepository\Storage;
use AC\Middleware;
use AC\Request;
use AC\Table;
use AC\Type\ListScreenId;
use Exception;
use WP_Screen;

class ListScreenTable implements Middleware {

	use ListScreenPermissionTrait;

	private $storage;

	private $list_screen_factory;

	private $wp_screen;

	private $preference;

	public function __construct(
		Storage $storage,
		ListScreenFactory $list_screen_factory,
		WP_Screen $wp_screen,
		Table\LayoutPreference $preference
	) {
		$this->storage = $storage;
		$this->list_screen_factory = $list_screen_factory;
		$this->wp_screen = $wp_screen;
		$this->preference = $preference;
	}

	private function get_first_list_screen(): ?ListScreen {
		$list_key = $this->get_list_key();

		if ( ! $list_key ) {
			return null;
		}

		$list_screens = $this->storage->find_all_by_user( $list_key, wp_get_current_user(), new ManualOrder() );

		if ( $list_screens->valid() ) {
			return $list_screens->current();
		}

		return $this->list_screen_factory->can_create( $list_key )
			? $this->list_screen_factory->create( $list_key )
			: null;
	}

	private function get_preference_list_screen(): ?ListScreen {
		$list_key = $this->get_list_key();

		if ( ! $list_key ) {
			return null;
		}

		try {
			$list_id = new ListScreenId( (string) $this->preference->get( $list_key ) );
		} catch ( Exception $e ) {
			return null;
		}

		$list_screen = $this->storage->find( $list_id );

		if ( ! $list_screen ) {
			return null;
		}

		if ( ! $this->user_is_assigned_to_list_screen( $list_screen, wp_get_current_user() ) ) {
			return null;
		}

		return $list_screen->get_key() === $this->get_list_key()
			? $list_screen
			: null;
	}

	private function get_requested_list_screen( Request $request ): ?ListScreen {
		try {
			$list_id = new ListScreenId( (string) $request->get( 'layout' ) );
		} catch ( Exception $e ) {
			return null;
		}

		$list_screen = $this->storage->find_by_user( $list_id, wp_get_current_user() );

		return $list_screen && $list_screen->get_key() === $this->get_list_key()
			? $list_screen
			: null;
	}

	private function get_list_key(): ?string {
		return $this->list_screen_factory->can_create_by_wp_screen( $this->wp_screen )
			? $this->list_screen_factory->create_by_wp_screen( $this->wp_screen )->get_key()
			: null;
	}

	private function get_list_screen( Request $request ): ?ListScreen {
		$list_screen = $this->get_requested_list_screen( $request );

		if ( ! $list_screen ) {
			$list_screen = $this->get_preference_list_screen();
		}

		return $list_screen ?: $this->get_first_list_screen();
	}

	public function handle( Request $request ) {
		$request->get_parameters()->merge( [
			'list_screen' => $this->get_list_screen( $request ),
		] );
	}

}