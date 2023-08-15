<?php
declare( strict_types=1 );

namespace AC\Table;

use AC\PostTypeRepository;
use AC\Type\ListKey;

class ListKeysFactory implements ListKeysFactoryInterface {

	private $post_type_repository;

	public function __construct( PostTypeRepository $post_type_repository ) {
		$this->post_type_repository = $post_type_repository;
	}

	public function create(): ListKeyCollection {
		$keys = new ListKeyCollection();

		foreach ( $this->post_type_repository->find_all() as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object ) {
				$keys->add( new ListKey( $post_type ) );
			}
		}

		$keys->add( new ListKey( 'wp-comments' ) );
		$keys->add( new ListKey( 'wp-users' ) );
		$keys->add( new ListKey( 'wp-media' ) );

		do_action( 'ac/list_keys', $keys );

		return $keys;
	}

}