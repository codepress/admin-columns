const npsUtils = require( "nps-utils" );
const core_path = '../assets/';

module.exports = {
	scripts : {
		build : {
			default : npsUtils.concurrent.nps( 'styles', 'scripts', 'languages' ),
			development : npsUtils.concurrent.nps( 'styles.development', 'scripts.development' ),
		},
		styles : {
			default : npsUtils.concurrent.nps( 'styles.production', 'styles.clean' ),
			production : `rimraf ${core_path}css/*.map && sass scss:${core_path}css/ --style=compressed --no-source-map`,
			development : `sass scss:${core_path}css/ -w`,
			clean : npsUtils.rimraf( `${core_path}css/*.map` )
		},
		scripts : {
			default : `webpack --mode=production`,
			development : `webpack --mode=development`,
		},
		languages : {
			default : 'nps "languages.build_pot" & nps "languages.pull_language"',
			build_pot : "node languages.js",
			pull_languages : "( cd .. ; ./getlangs )"
		}
	}
};