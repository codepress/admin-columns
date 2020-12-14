let path = require( 'path' );

let config = {
	entry : {
		'admin-general' : './js/admin-general.ts',
		'admin-page-columns' : ['./js/admin-page-columns.ts'],
		'admin-page-addons' : './js/admin-page-addons.ts',
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
			{ loader : 'ts-loader' }
		]
	},
	resolve : {
		extensions : ['.tsx', '.ts', '.js'],
	},
	externals : {
		jquery : 'jQuery',
		jQuery : 'jQuery'
	},
	stats : {
		colors : true
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