<?php

/**
 * @property int   $id
 * @property mixed $value
 */
final class AC_ValueFormatter {

	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var array
	 */
	private $formatters = array();

	/**
	 * @param int $id
	 */
	public function __construct( $id, $value = null ) {
		$this->id = absint( $id );
		$this->value = $value;
	}

	/**
	 * @param $key
	 *
	 * @return null
	 */
	public function __get( $property ) {
		$accessible = array( 'id', 'value' );

		if ( ! in_array( $property, $accessible, true ) ) {
			return null;
		}

		return $this->$property;
	}

	/**
	 * Array of object to check for AC_Settings_FormatInterface objects
	 *
	 * @param Iterator|array $formatters
	 *
	 * @return $this;
	 */
	public function add_formatters( $formatters ) {
		foreach ( $formatters as $formatter ) {
			if ( $formatter instanceof AC_Settings_FormatInterface ) {
				$this->add_formatter( $formatter );
			}
		}

		return $this;
	}

	/**
	 * @param AC_Settings_FormatInterface $formatter
	 *
	 * @return $this
	 */
	public function add_formatter( AC_Settings_FormatInterface $formatter ) {
		$this->formatters[ $formatter::FORMAT_PRIORITY ][] = $formatter;

		return $this;
	}

	/**
	 * Apply formatters
	 *
	 * @return $this
	 */
	public function apply_formatters() {
		ksort( $this->formatters );

		foreach ( $this->formatters as $priority => $formatters ) {
			/* @var $formatter AC_Settings_FormatInterface */
			foreach ( $formatters as $formatter ) {
				if ( $this->value instanceof AC_Collection ) {
					foreach ( $this->value as $self ) {
						$formatter->format( $self );
					}
				} else {
					$formatter->format( $this );
				}
			}
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->apply_formatters()->value;
	}

}