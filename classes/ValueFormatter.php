<?php

final class AC_ValueFormatter {

	/**
	 * @var mixed
	 */
	public $value;

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $separator;

	/**
	 * @var array
	 */
	private $formatters = array();

	/**
	 * @param int|null $id
	 */
	public function __construct( $id = null ) {
		$this->set_id( $id );
		$this->set_separator( ', ' );
	}

	/**
	 * @param int $id
	 *
	 * @return $this
	 */
	protected function set_id( $id ) {
		$this->id = absint( $id );

		return $this;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_separator() {
		return $this->separator;
	}

	/**
	 * @param string $separator
	 *
	 * @return $this
	 */
	public function set_separator( $separator ) {
		$this->separator = $separator;

		return $this;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set_value( $value ) {
		$this->value = $value;

		return $this;
	}

	/**
	 * @param AC_Settings_FormatInterface $formatter
	 *
	 * @return $this
	 */
	public function add_formatter( AC_Settings_FormatInterface $formatter ) {
		$priority = absint( $formatter->get_format_priority() );
		$this->formatters[ $priority ][] = $formatter;

		return $this;
	}

	/**
	 * Apply formatters
	 *
	 * @param string $separator Used when the result is an AC_Collection
	 *
	 * @return string
	 */
	public function apply_formatters() {
		ksort( $this->formatters );

		foreach ( $this->formatters as $priority => $formatters ) {
			/* @var $formatter AC_Settings_FormatInterface */
			foreach ( $formatters as $formatter ) {
				if ( $this->value instanceof AC_Collection ) {
					foreach ( $this->value as $k => $value ) {

						// Allow formatters to return an array of id's
						if ( ! ( $value instanceof AC_ValueFormatter ) ) {
							$value = new AC_ValueFormatter( $value );

							$this->value->put( $k, $value );
						}

						$formatter->format( $value );
					}
				} else {
					$formatter->format( $this );
				}
			}
		}

		if ( $this->value instanceof AC_Collection ) {
			$this->value = $this->value->implode( $this->separator );
		}

		return $this->value;
	}

	public function __toString() {
		return $this->apply_formatters();
	}

}