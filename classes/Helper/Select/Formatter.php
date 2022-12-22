<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;

// TODO this is not a Formatter but a OptionCollection..
class Formatter extends ArrayIterator {

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

		parent::__construct( $this->get_labels() );
	}

	/**
	 * @param mixed $value
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

		$labels = $this->get_labels_unique( $labels );

		return $this->get_options( $labels );
	}

	protected function get_labels_unique( array $labels ): array {
		$duplicates = array_diff_assoc( $labels, array_unique( $labels ) );

		foreach ( $labels as $value => $label ) {
			if ( ! in_array( $label, $duplicates, true ) ) {
				continue;
			}

			$entity = $this->get_entity( $value );
			$unique_label = $this->unique_value_formatter->format_value_unique( $entity );

			$labels[ $value ] = $this->render_label_unique(
				$label,
				$unique_label
			);
		}

		return $labels;
	}

	protected function render_label_unique( string $label, string $unique_label ): string {
		return sprintf( '%s (%s)', $label, $unique_label );
	}

	private function get_options( array $labels ): array {
		$options = [];

		foreach ( $labels as $value => $label ) {
			$options[] = new Option( $value, $label );
		}

		return $options;
	}

}