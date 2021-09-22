<?php

namespace AC\Settings;

use AC\Storage\KeyValuePair;

class Option {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var GeneralOption
	 */
	private $storage;

	public function __construct( $name, KeyValuePair $storage = null ) {
		$this->name = $name;
		$this->storage = $storage ?: new GeneralOption();
	}

	/**
	 * @return bool
	 */
	public function is_empty() {
		return in_array( $this->get(), [ null, false ], true );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function get() {
		$values = $this->storage->get();

		if ( ! $values || ! array_key_exists( $this->name, $values ) ) {
			return null;
		}

		return $values[ $this->name ];
	}

	/**
	 * @param mixed $value
	 */
	public function save( $value ) {
		$values = $this->storage->get();

		if ( false === $values ) {
			$values = [];
		}

		$values[ $this->name ] = $value;

		$this->storage->save( $values );
	}

	/**
	 * @param string $name
	 */
	public function delete() {
		$values = $this->storage->get();

		if ( empty( $values ) ) {
			return;
		}

		unset( $values[ $this->name ] );

		$this->storage->save( $values );
	}

}