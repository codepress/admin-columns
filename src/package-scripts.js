let core_path = '../assets/';

module.exports = {
	scripts : {
		build : {
			production : 'nps "styles.production" & nps "scripts.production"',
			development : 'nps "styles.development" & nps "scripts.development"',
		},
		styles : {
			production : `rimraf ${core_path}css/*.map && node-sass scss --output-style compressed --o ${core_path}css/`,
			development : `nps "styles.production" && node-sass scss -w --source-map true --output-style compressed --o ${core_path}css/`
		},
		scripts : {
			production : `babel js --presets es2015 --out-dir ${core_path}js/ --minified`,
			development : `babel js --presets es2015 --out-dir ${core_path}js/ --minified --watch`,
		},
		webfont : {
			build : `nps "webfont.create_fonts" & nps "webfont.copy_fonts" & nps "webfont.copy_scss"`,
			create_fonts : `webfont svg/*.svg --dest webfont/fonts --dest-styles webfont/scss`,
			copy_fonts : `cp -a webfont/fonts/. ${core_path}fonts/`,
			copy_scss : `cp webfont/scss/template.scss scss/_webfont.scss`,
		},

	}
};
