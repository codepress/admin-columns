let path = require( 'path' );

let config = {
	entry : {
		'admin-general' : './js/admin-general.js',
		'admin-page-columns' : './js/admin-page-columns.js',
		'message-review' : './js/message-review.js',
		'notice-dismissible' : './js/notice-dismissible.js',
		'table' : './js/table.js'
	},
	output : {
		path : path.resolve( __dirname, '../assets/js' ),
		filename : '[name].js',
	},
	module : {
		rules : [
			{
				test : /\.js$/,
				exclude : /node_modules/,
				use : {
					loader : 'babel-loader',
					options : {
						presets : [ [ "@babel/preset-env", {
							"targets" : {
								"browsers" : [ "ie 11" ]
							}

						} ] ]
					}
				}
			}
		]
	},
	stats : {
		colors : true
	},
	externals : {
		jquery : 'jQuery'
	},

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