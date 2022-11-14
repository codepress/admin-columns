<?php

namespace AC\Translation;

class BaseTranslation implements Translation {

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @param string $data
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	public function get_translation(): array {
		return $this->data;
	}

	/**
	 * @param string $suffix
	 *
	 * @return self
	 */
	public function with_translation( Translation $translation ) {
		$data = $this->get_translation() + $translation->get_translation();

		return new self( $data );
	}

}