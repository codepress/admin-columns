const npsUtils = require( "nps-utils" );
const core_path = '../assets/';

module.exports = {
	scripts : {
		build : {
			default : npsUtils.series.nps(
				'styles.build --style=compressed --no-source-map',
				'scripts --mode=production',
				'tailwind.build',
				'clean'
			),
			development : npsUtils.concurrent.nps(
				'styles.build -w',
				'scripts --mode=development',
				'tailwind.watch'
			),
		},
		//clean_old : npsUtils.rimraf( `${core_path}*/*.map` ),
		clean : npsUtils.rimraf( `${core_path}*/*.map` ),
		tailwind : {
			build : 'npx tailwindcss -i ./tailwind/ac-utilities.css -o ../assets/css/utilities.css',
			watch : 'npx tailwindcss -i ./tailwind/ac-utilities.css -o ../assets/css/utilities.css --watch',
		},
		styles : `sass scss:${core_path}css/`,
		scripts : 'webpack',
		languages : {
			default : npsUtils.series.nps( 'languages.build_pot', 'languages.pull' ),
			build_pot : 'node languages.js',
			pull : 'node scripts/pull-languages.js'
		}
	}
};