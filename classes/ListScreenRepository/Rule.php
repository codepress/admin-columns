<?php

namespace AC\ListScreenRepository;

interface Rule {

	const TYPE = 'type';
	const ID = 'id';
	const GROUP = 'group';

	/**
	 * @param array $args
	 *
	 * @return bool
	 */
	public function match( array $args );

}