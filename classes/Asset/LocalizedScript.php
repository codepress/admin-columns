<?php

namespace AC\Asset;

use AC\Translation\Translation;

class LocalizedScript {

	private $handle;

	private $name;

	private $translation;

	public function __construct( string $handle, string $name, Translation $translation ) {
		$this->handle = $handle;
		$this->name = $name;
		$this->translation = $translation;
	}

	public function localize(): void {
		wp_localize_script(
			$this->handle,
			$this->name,
			$this->translation->get_translation()
		);
	}

}