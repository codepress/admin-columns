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
	 * Cast an array with ids to an collection with AC_ValueFormatter[]
	 *
	 * @param array $ids
	 *
	 * @return AC_Collection
	 */
	public static function cast_ids( array $ids ) {
		$collection = new AC_Collection;

		foreach ( $ids as $id ) {
			$collection->push( new AC_ValueFormatter( $id ) );
		}

		return $collection;
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
		$this->formatters[] = $formatter;

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
		foreach ( $this->formatters as $formatter ) {
			if ( $this->value instanceof AC_Collection ) {
				if ( $formatter instanceof AC_Settings_FormatValueInterface ) {
					foreach ( $this->value as $value ) {
						$formatter->format( $value );
					}
				} elseif ( $formatter instanceof AC_Settings_FormatCollectionInterface ) {
					$formatter->format( $this->value, $this->id );
				}
			} elseif ( $formatter instanceof AC_Settings_FormatValueInterface ) {
				$formatter->format( $this );
			}
		}

		if ( $this->value instanceof AC_Collection ) {
			$this->value = $this->value->implode( $this->separator );
		}

		return (string) $this->value;
	}

	public function __toString() {
		return $this->apply_formatters();
	}

}