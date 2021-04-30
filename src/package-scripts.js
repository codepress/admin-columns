let core_path = '../assets/';

module.exports = {
	scripts : {
		build : {
			production : 'nps "styles.production" & nps "scripts.production"',
			development : 'nps "styles.development" & nps "scripts.development"',
		},
		styles : {
			production : `rimraf ${core_path}css/*.map && sass scss:${core_path}css/ --style=compressed --no-source-map`,
			development : `sass scss:${core_path}css/ -w`
		},
		scripts : {
			production : `webpack --mode=production`,
			development : `webpack --mode=development`,
		},
		webfont : {
			build : `nps "webfont.copy_fonts" & nps "webfont.copy_scss" & nps "styles.production"`,
			create_fonts : `webfont svg/*.svg --dest webfont/fonts --dest-styles webfont/scss`,
			copy_fonts : `cp -a webfont/fonts/. ${core_path}fonts/`,
			copy_scss : `cp webfont/scss/template.scss scss/_webfont.scss`,
		},
		languages : {
			production : 'nps "languages.build_pot" & nps "languages.pull_language"',
			build_pot : "node languages.js",
			pull_languages : "( cd .. ; ./getlangs )"
		}
	}
};
