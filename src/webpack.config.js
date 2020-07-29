let path = require( 'path' );

let config = {
	entry : {
		'admin-general' : './js/admin-general.js',
		'admin-page-columns' : ['./js/admin-page-columns.js'],
		'admin-page-addons' : './js/admin-page-addons.js',
		'message-review' : './js/message-review.ts',
		'table' : './js/table.ts'
	},
	output : {
		path : path.resolve( __dirname, '../assets/js' ),
		filename : '[name].js',
	},
	module : {
		rules : [
			{
				test : /\.(t|j)sx?$/,
				exclude : /node_modules(?!(\/|\\)query-string)/,
				use : [
					{
						loader : 'babel-loader',
						options : {
							presets : ['@babel/preset-env']
						}
					},
					{
						loader : 'ts-loader',
					}
				]
			}
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