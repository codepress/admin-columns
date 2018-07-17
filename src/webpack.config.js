let path = require( 'path' );

let config = {
	entry : './js-new/admin-page-columns.js',
	output : {
		path : path.resolve( __dirname, '../assets/js' ),
		filename : 'admin-page-columns.js',
	},
	module : {
		rules : [
			{
				test : /\.js$/,
				exclude : /node_modules/,
				use : {
					loader : 'babel-loader',
					options : {
						presets : [ '@babel/preset-env' ]
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