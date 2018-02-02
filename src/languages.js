const wpPot = require( 'wp-pot' );

wpPot( {
	destFile : '../languages/codepress-admin-columns.pot',
	domain : 'codepress-admin-columns',
	relativeTo : '../',
	lastTranslator : 'Codepress <info@codepress.nl',
	team : 'Admin Columns <info@admincolumns.com>',
	package : 'Admin Columns',
	src : [
		'../*.php', // root
		'../**/*.php' // subfolders
	]
} );