<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;

abstract class Group extends ArrayIterator {

	/**
	 * @var Formatter
	 */
	private $formatter;

	/**
	 * @param Formatter $formatter
	 */
	public function __construct( Formatter $formatter ) {
		$this->formatter = $formatter;

		parent::__construct( $this->get_group() );
	}

	/**
	 * @param array $groups
	 *
	 * @return array
	 */
	protected function sort( array $groups ) {
		uksort( $groups, 'strnatcmp' );

		return $groups;
	}

	/**
	 * @return OptionGroup[]
	 */
	protected function get_group() {
		$groups = [];

		foreach ( $this->formatter as $option ) {
			$label = $this->get_label(
				$this->formatter->get_entity( $option->get_value() ),
				$option
			);

			$groups[ $label ][] = $option;
		}

		return $this->get_option_groups( $this->sort( $groups ) );
	}

	/**
	 * @param array $groups
	 *
	 * @return OptionGroup[]
	 */
	protected function get_option_groups( array $groups ) {
		$option_groups = [];

		foreach ( $groups as $label => $options ) {
			$option_groups[] = new OptionGroup( $label, $options );
		}

		return $option_groups;
	}

	/**
	 * @param        $entity
	 * @param Option $option
	 *
	 * @return string
	 */
	abstract protected function get_label( $entity, Option $option );

}