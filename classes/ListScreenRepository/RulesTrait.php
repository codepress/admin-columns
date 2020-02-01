<?php

namespace AC\ListScreenRepository;

use LogicException;

trait RulesTrait {

	/**
	 * @var Rules
	 */
	protected $rules;

	/**
	 * @return bool
	 */
	public function has_rules() {
		return $this->rules !== null;
	}

	/**
	 * @return Rules
	 */
	public function get_rules() {
		if ( ! $this->has_rules() ) {
			throw new LogicException( 'No rules defined.' );
		}

		return $this->rules;
	}

	/**
	 * @param Rules $rules
	 *
	 * @return void
	 */
	public function set_rules( Rules $rules ) {
		$this->rules = $rules;
	}

}