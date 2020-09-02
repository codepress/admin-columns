<?php

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;
use AC\Type\Url;

class UtmTags implements QueryAware {

	use QueryAwareTrait;

	const ARG_SOURCE = 'utm_source';
	const ARG_MEDIUM = 'utm_medium';
	const ARG_CONTENT = 'utm_content';
	const ARG_CAMPAIGN = 'utm_campaign';

	public function __construct( Url $url, $medium, $content = null, $campaign = null ) {
		$this->url = $url->get_url();
		$this->add( [
			self::ARG_SOURCE => 'plugin-installation',
			self::ARG_MEDIUM => $medium,
		] );

		if ( $content ) {
			$this->add_content( $content );
		}

		if ( $campaign ) {
			$this->add_campaign( $campaign );
		}
	}

	public function add_medium( $medium ) {
		$this->add_one( self::ARG_MEDIUM, $medium );

		return $this;
	}

	public function add_content( $content ) {
		$this->add_one( self::ARG_CONTENT, $content );

		return $this;
	}

	public function add_campaign( $campaign ) {
		$this->add_one( self::ARG_CAMPAIGN, $campaign );

		return $this;
	}

}