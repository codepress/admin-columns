<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;

abstract class Formatter extends ArrayIterator {

	private $entities;

	protected $value_formatter;

	protected $unique_value_formatter;

	public function __construct(
		Entities $entities,
		ValueFormatter $value_formatter,
		UnqiueValueFormatter $unique_value_formatter = null
	) {
		$this->entities = $entities;
		$this->value_formatter = $value_formatter;
		$this->unique_value_formatter = $unique_value_formatter;

		// TODO implement null formatters

		parent::__construct( $this->get_labels() );
	}

	public function get_entities(): Entities {
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
	protected function get_labels(): array {
		$labels = [];

		foreach ( $this->entities as $value => $entity ) {
			$labels[ $value ] = $this->value_formatter->format_value( $entity );
		}

		if ( $this->unique_value_formatter ) {
			$labels = $this->get_labels_unique( $labels );
		}

		return $this->get_options( $labels );
	}

	/**
	 * @param $entity
	 *
	 * @return string
	 */
	// TODO remove
	protected abstract function get_label( $entity );

	protected function get_labels_unique( array $labels ): array {
		$duplicates = array_diff_assoc( $labels, array_unique( $labels ) );

		foreach ( $labels as $value => $label ) {
			if ( ! in_array( $label, $duplicates, true ) ) {
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
	protected function get_label_unique( $label, $entity ): string {
		return sprintf( '%s (%s)', $label, $this->unique_value_formatter->format_value_unique( $entity ) );
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