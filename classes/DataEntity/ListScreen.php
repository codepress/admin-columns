<?php

namespace AC\DataEntity;

class ListScreen {

	/** @var array */
	private $columns;

	/** @var array */
	private $settings;

	/** @var string */
	private $type;

	/** @var string */
	private $subtype;

	public function __construct( array $columns, array $settings, $type, $subtype = null ) {
		$this->columns = $columns;
		$this->settings = $settings;
		$this->type = $type;
		$this->subtype = $subtype;
	}

	/**
	 * @return array
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function get_subtype() {
		return $this->subtype;
	}

}