<?php

class AC_Value {

	/**
	 * @var AC_Collection|string
	 */
	// TODO: name $value is ok-ish. Maybe use data or format or...
	protected $value;

	/**
	 * @var int
	 */
	protected $id = 0;

	/**
	 * @var array
	 */
	protected $wrappers = array();

	/**
	 * @param null|int $id
	 */
	public function __construct( $id = null ) {
		$this->set_id( $id );
	}

	/**
	 * @param AC_Collection|string $value
	 *
	 * @return $this
	 */
	// TODO: maybe add a __set for just value?
	public function set( $value ) {
		$this->value = $value;

		return $this;
	}

	/**
	 * @return AC_Collection|string
	 */
	public function get() {
		return $this->value;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );

		return $this;
	}

	/**
	 * Add a wrapper to the value
	 *
	 * @param string $before
	 * @param string $after
	 * @param int    $priority
	 *
	 * @return $this
	 */
	public function add_wrapper( $before, $after, $priority = 10 ) {
		$this->wrappers[ absint( $priority ) ][] = (object) array(
			'before' => $before,
			'after'  => $after,
		);

		ksort( $this->wrappers );

		return $this;
	}

	/**
	 * Return value and apply wrappers
	 *
	 * @return string
	 */
	public function render() {
		$value = $this->value;

		foreach ( $this->wrappers as $priority => $wrappers ) {
			foreach ( $wrappers as $wrapper ) {
				$value = $wrapper->before . $value . $wrapper->after;
			}
		}

		return $value;
	}

}