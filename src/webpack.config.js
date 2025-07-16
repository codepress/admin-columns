const path = require( 'path' );
const sveltePreprocess = require( "svelte-preprocess" );

let config = {
	entry : {
		'admin-page-columns' : [ './js/admin-page-columns.ts' ],
		'admin-page-settings' : [ './js/admin-page-settings.ts' ],
		'admin-page-addons' : './js/admin-page-addons.ts',
		'global-translations' : './js/global-translations.ts',
		'notice-dismissible' : './js/notice-dismissible.ts',
		'table' : './js/table.ts'
	},
	output : {
		path : path.resolve( __dirname, '../assets/js' ),
		filename : '[name].js',
	},
	module : {
		rules : [
			{
				test : /\.(html|svelte)$/,
				use : [
					{ loader : "babel-loader" },
					{
						loader : "svelte-loader",
						options : {
							preprocess : sveltePreprocess( {} ),
						},
					},
				],
			},
			{
				test : /\.ts$/,
				exclude : /node_modules/,
				use : 'ts-loader'
			},
		]
	},
	resolve : {
		conditionNames : [ 'svelte', 'browser', 'import' ],
		extensions : [ '.mjs', '.js', '.svelte', '.ts' ],
		mainFields : [ 'svelte', 'browser', 'module', 'main' ],
		alias : {
			svelte : path.resolve( 'node_modules', 'svelte/src/runtime' ),
			ACUi : path.resolve( __dirname, 'ui' ),
			"@ac/material-icons" : path.resolve( __dirname, 'packages/material-icons' ),
		},
	},
	externals : {
		jquery : 'jQuery',
		jQuery : 'jQuery'
	},
	stats : 'minimal',
	performance: {
		hints: false
	}

};

module.exports = ( env, argv ) => {

	if ( argv.mode === 'development' ) {

		config.devtool = 'source-map';
		config.watch = true;
		config.watchOptions = {
			ignored : /node_modules/
		}

	}

	return config;
};