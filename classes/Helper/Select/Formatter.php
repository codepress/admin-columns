<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;

abstract class Formatter extends ArrayIterator {

	/**
	 * @var Entities
	 */
	private $entities;

	/**
	 * @var Value
	 */
	protected $value;

	public function __construct( Entities $entities, Value $value = null ) {
		$this->entities = $entities;
		$this->value = $value;

		parent::__construct( $this->get_labels() );
	}

	/**
	 * @return Entities
	 */
	public function get_entities() {
		return $this->entities;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function get_entity( $value ) {
		if ( ! $this->entities->has_offset( $value ) ) {
			return false;
		}

		return $this->entities->get_offset( $value );
	}

	/**
	 * @return Option[]
	 */
	protected function get_labels() {
		$labels = [];

		foreach ( $this->entities as $value => $entity ) {
			$labels[ $value ] = $this->get_label( $entity );
		}

		if ( $this->value ) {
			$labels = $this->get_labels_unique( $labels );
		}

		return $this->get_options( $labels );
	}

	/**
	 * @param $entity
	 *
	 * @return string
	 */
	protected abstract function get_label( $entity );

	/**
	 * @param array $labels
	 *
	 * @return array
	 */
	protected function get_labels_unique( array $labels ) {
		$duplicates = array_diff_assoc( $labels, array_unique( $labels ) );

		foreach ( $labels as $value => $label ) {
			if ( ! in_array( $label, $duplicates ) ) {
				continue;
			}

			$labels[ $value ] = $this->get_label_unique( $label, $this->get_entity( $value ) );
		}

		return $labels;
	}

	/**
	 * @param string $label
	 * @param mixed  $entity
	 *
	 * @return string
	 */
	protected function get_label_unique( $label, $entity ) {
		return $label . sprintf( ' (%s)', $this->value->get_value( $entity ) );
	}

	/**
	 * @param array $labels
	 *
	 * @return Option[]
	 */
	private function get_options( array $labels ): array {
		$options = [];

		foreach ( $labels as $value => $label ) {
			$options[] = new Option( $value, $label );
		}

		return $options;
	}

}