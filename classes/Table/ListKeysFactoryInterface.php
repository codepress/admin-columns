<?php
declare( strict_types=1 );

namespace AC\Table;

interface ListKeysFactoryInterface {

	public function create(): ListKeyCollection;

}