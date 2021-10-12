<?php

namespace AC\Type\Url;

use AC\Type;

class Site implements Type\Url {

	use Path;

	const URL = 'https://www.admincolumns.com';

	const PAGE_PRICING = '/pricing-purchase';
	const PAGE_ABOUT_PRO = '/admin-columns-pro';
	const PAGE_ACCOUNT_SUBSCRIPTIONS = '/my-account/subscriptions';
	const PAGE_FORUM = '/topics';
	const PAGE_FORUM_BETA = '/forums/forum/beta-feedback/';
	const PAGE_ADDON_ACF = '/advanced-custom-fields';
	const PAGE_ADDON_BUDDYPRESS = '/buddypress';
	const PAGE_ADDON_GRAVITYFORMS = '/gravity-forms';
	const PAGE_ADDON_EVENTS_CALENDAR = '/events-calendar';
	const PAGE_ADDON_METABOX = '/meta-box-integration';
	const PAGE_ADDON_JETENGINE = '/jetengine';
	const PAGE_ADDON_NINJA_FORMS = '/ninja-forms';
	const PAGE_ADDON_PODS = '/pods';
	const PAGE_ADDON_TOOLSET_TYPES = '/toolset-types';
	const PAGE_ADDON_WOOCOMMERCE = '/woocommerce-columns';
	const PAGE_ADDON_YOAST_SEO = '/yoast-seo';

	/**
	 * @param string $path
	 */
	public function __construct( $path = null ) {
		if ( $path ) {
			$this->set_path( $path );
		}
	}

	public function get_url() {
		return self::URL . $this->get_path();
	}

}