const npsUtils = require( "nps-utils" );
const core_path = '../assets/';

module.exports = {
	scripts : {
		build : {
			default : npsUtils.series.nps(
				'styles.build --style=compressed --no-source-map',
				'scripts --mode=production',
				'languages',
				'clean'
			),
			lite : npsUtils.series.nps(
				'styles.build --style=compressed --no-source-map',
				'scripts --mode=production',
				'clean'
			),
			development : npsUtils.concurrent.nps(
				'styles.build -w',
				'scripts --mode=development'
			),
		},
		//clean_old : npsUtils.rimraf( `${core_path}*/*.map` ),
		clean : npsUtils.rimraf( `${core_path}*/*.map` ),
		styles : `sass scss:${core_path}css/`,
		scripts : 'webpack',
		languages : {
			default : 'nps "languages.build_pot" & nps "languages.pull_language"',
			build_pot : "node languages.js",
			pull_languages : "( cd .. ; ./getlangs )"
		}
	}
};