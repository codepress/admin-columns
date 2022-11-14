<?php

namespace AC\Asset;

use AC\Translation\Translation;

class LocalizedScript {

	public function __construct( Script $script, string $name, Translation $translation ) {
		wp_localize_script( $script->get_handle(), $name, $translation->get_translation() );
	}

}