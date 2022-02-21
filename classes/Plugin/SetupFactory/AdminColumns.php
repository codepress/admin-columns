<?php

namespace AC\Plugin\SetupFactory;

use AC\Plugin\Install;
use AC\Plugin\InstallCollection;
use AC\Plugin\SetupFactory;
use AC\Plugin\Update;
use AC\Plugin\UpdateCollection;

final class AdminColumns extends SetupFactory {

	public function create( $type ) {

		switch ( $type ) {
			case self::NETWORK:
				$this->installers = new InstallCollection( [
					new Install\Capabilities(),
				] );
				break;
			case self::SITE:
				$this->installers = new InstallCollection( [
					new Install\Capabilities(),
					new Install\Database(),
				] );
				$this->updates = new UpdateCollection( [
					new Update\V3005(),
					new Update\V3007(),
					new Update\V3201(),
					new Update\V4000(),
				] );
				break;
		}

		return parent::create( $type );
	}

}