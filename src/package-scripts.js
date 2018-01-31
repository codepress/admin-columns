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

	}
};
