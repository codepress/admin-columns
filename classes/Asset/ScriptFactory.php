<?php declare( strict_types=1 );

namespace AC\Asset;

interface ScriptFactory {

	public function create(): Script;

}