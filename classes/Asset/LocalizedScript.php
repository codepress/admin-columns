<?php

namespace AC\Asset;

use AC\Translation\Translation;

class LocalizedScript {

	private Script $script;

	private string $name;

	private Translation $translation;

	public function __construct( Script $script, string $name, Translation $translation ) {
		$this->script = $script;
		$this->name = $name;
		$this->translation = $translation;
	}

	public function localize(): void {
		wp_localize_script(
			$this->script->get_handle(),
			$this->name,
			$this->translation->get_translation()
		);
	}

}