<?php

/**
 * Class AC_ValueFormatter
 *
 */
final class AC_ValueFormatter {

	/**
	 * @var mixed
	 */
	public $value;

	/**
	 * @var mixed
	 */
	private $original_value;

	/**
	 * @var string
	 */
	private $separator;

	/**
	 * @var array
	 */
	private $formatters = array();

	/**
	 * @param mixed $value
	 */
	public function __construct( $value ) {
		$this->value = $value;
		$this->original_value = $value;

		$this->set_separator( ', ' );
	}

	/**
	 * Create an collection with AC_ValueFormatter objects based on an array
	 *
	 * @param array    $values
	 * @param null|int $id
	 *
	 * @return AC_Collection
	 */
	public static function create_collection( array $values ) {
		$collection = new AC_Collection;

		foreach ( $values as $value ) {
			$collection->push( new AC_ValueFormatter( $value ) );
		}

		return $collection;
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
	 * @return mixed
	 */
	public function get_original_value() {
		return $this->original_value;
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
					$formatter->format( $this->value, $this->original_value );
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