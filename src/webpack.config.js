const path = require( 'path' );
const sveltePreprocess = require( "svelte-preprocess" );

let config = {
	entry : {
		'admin-general' : './js/admin-general.ts',
		'admin-page-columns' : ['./js/admin-page-columns.ts'],
		'admin-page-settings' : ['./js/admin-page-settings.ts'],
		'admin-page-addons' : './js/admin-page-addons.ts',
		'global-translations' : './js/global-translations.ts',
		'message-review' : './js/message-review.ts',
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
		extensions : ['.mjs', '.js', '.svelte', '.ts'],
		mainFields : ['svelte', 'browser', 'module', 'main'],
		alias : {
			svelte : path.resolve( 'node_modules', 'svelte' ),
			ACUi : path.resolve( __dirname, 'ui' ),
		},
	},
	externals : {
		jquery : 'jQuery',
		jQuery : 'jQuery'
	},
	stats : 'minimal'

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