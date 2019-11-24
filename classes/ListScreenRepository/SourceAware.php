<?php
namespace AC\ListScreenRepository;

use AC\ListScreen;

interface SourceAware {

	/**
	 * @return string
	 */
	public function getSource( ListScreen $listScreen );

}