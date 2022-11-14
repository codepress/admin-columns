<?php

namespace AC\Translation;

class BaseTranslation implements Translation {

	/**
	 * @var array
	 */
	private $data;

	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function get_translation(): array {
		return $this->data;
	}
	
	public function with_translation( Translation $translation ) {
		$data = $this->get_translation() + $translation->get_translation();

		return new self( $data );
	}

}