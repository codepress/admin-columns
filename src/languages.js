const wpPot = require( 'wp-pot' );

wpPot( {
	destFile : '../languages/codepress-admin-columns.pot',
	domain : 'codepress-admin-columns',
	relativeTo : '../../',
	lastTranslator : 'Codepress <info@codepress.nl',
	team : 'Admin Columns <info@admincolumns.com>',
	package : 'Admin Columns Pro',
	src : [

		// Exclude Core
		'../!(admin-columns)/**/*.php',

		// Add Pro root
		'../*.php',

		// ACF add-on
		'../../cac-addon-acf/*.php',
		'../../cac-addon-acf/**/*.php',

		// WooCommerce add-on
		'../../cac-addon-woocommerce/*.php',
		'../../cac-addon-woocommerce/**/*.php',

		// Pods add-on
		'../../ac-addon-pods/*.php',
		'../../ac-addon-pods/**/*.php',

		// Types add-on
		'../../ac-addon-types/*.php',
		'../../ac-addon-types/**/*.php',

		// Ninja Forms add-on
		'../../ac-addon-ninja-forms/*.php',
		'../../ac-addon-ninja-forms/**/*.php',

		// BuddyPress add-on
		'../../ac-addon-buddypress/*.php',
		'../../ac-addon-buddypress/**/*.php',

		// Events Calendar add-on
		'../../ac-addon-events-calendar/*.php',
		'../../ac-addon-events-calendar/**/*.php',

	]
} );