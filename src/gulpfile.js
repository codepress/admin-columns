/*
 *  This script will:
 *  All js files in  ../assets/js will auto minify on save (when running watch)
 *  All less files in less will auto compile less and minify (when running watch)
 */

var gulp = require( 'gulp' ),
	gutil = require( 'gulp-util' ),
	livereload = require( 'gulp-livereload' ),
	sass = require( 'gulp-sass' ),
	cssnano = require( 'gulp-cssnano' ),
	rename = require( 'gulp-rename' ),
	uglify = require( 'gulp-uglify' ),
	plumber = require( 'gulp-plumber' ),
	wpPot = require( 'gulp-wp-pot' ),
	gettext = require( 'gulp-gettext' ),
	iconfont = require( 'gulp-iconfont' ),
	consolidate = require( 'gulp-consolidate' );

var iconfontdir = "iconfont/";

var onError = function( e ) {
	gutil.beep();
	console.log( e );
	this.emit( 'end' );
};

gulp.task( 'scripts', function() {
	return gulp.src( [
		"../assets/js/**/!(*.min.js)",
	] )
		.pipe( plumber( { errorHandler : onError } ) )
		.pipe( rename( { suffix : '.min' } ) )
		.pipe( uglify() )
		.pipe( gulp.dest( '../assets/js' ) );
} );

gulp.task( 'styles', function() {
	return gulp.src( [
		'scss/admin-page-addons.scss',
		'scss/admin-page-columns.scss',
		'scss/admin-page-settings.scss',
		'scss/admin-page-help.scss',
		'scss/admin-general.scss',
		'scss/admin-welcome.scss',
		'scss/table.scss',
		'scss/message.scss'
	] )
		.pipe( plumber( { errorHandler : onError } ) )
		.pipe( sass() )
		.pipe( gulp.dest( '../assets/css' ) )
		.pipe( cssnano() )
		.pipe( rename( { suffix : '.min' } ) )
		.pipe( gulp.dest( '../assets/css' ) );
} );

gulp.task( 'language', function() {
	return gulp.src( [
		'../*.php', // root
		'../**/*.php' // subfolders
	] )
		.pipe( wpPot( {
			domain : 'codepress-admin-columns',
			destFile : 'codepress-admin-columns.pot',
			package : 'Admin Columns',
			bugReport : 'https://www.admincolumns.com',
			lastTranslator : 'Codepress <info@codepress.nl',
			team : 'Admin Columns <info@admincolumns.com>'
		} ) )
		.pipe( gulp.dest( '../languages' ) );
} );

/**
 * Create font files and a Sass file for your source repo, based on all SVG images in de specified folder
 */
gulp.task( 'iconfontBuild', function() {
	return gulp.src( [ 'svg/*.svg' ] )
		.pipe( iconfont( {
			fontName : 'cpac_icons',
			normalize : true,
			fontHeight : 1001
		} ) )
		.on( 'glyphs', function( glyphs, options ) {
			gulp.src( iconfontdir + 'tpl_iconfont.scss' )
				.pipe( consolidate( 'lodash', {
					glyphs : glyphs,
					fontName : options.fontName,
					fontPath : "../fonts/"
				} ) )
				.pipe( rename( '_iconfont.scss' ) )
				.pipe( gulp.dest( iconfontdir + 'scss' ) );
		} )
		.pipe( gulp.dest( iconfontdir + 'fonts' ) );
} );

/**
 * Complete taks and copy Sass files to source folder and fonts to assets
 */
gulp.task( 'iconfont', [ 'iconfontBuild' ], function() {
	gulp.src( iconfontdir + 'fonts/*.*' )
		.pipe( gulp.dest( '../assets/fonts' ) );

	gulp.src( iconfontdir + 'scss/*.*' )
		.pipe( gulp.dest( 'scss' ) );
} );

/*
 gulp.task('compile-language', function() {
 Moved to bash, run ./generate-language
 Be sure that you have transifex cpi and gettext
 });
 */

gulp.task( 'watch', [ 'default' ], function() {
	gulp.watch( 'scss/**/*.scss', [ 'styles' ] );
	gulp.watch( '../assets/js/**/!(*.min.js)', [ 'scripts' ] );

	livereload.listen();

	gulp.watch( [ '../assets/**' ] ).on( 'change', livereload.changed );
} );

gulp.task( 'default', [ 'styles', 'scripts' ], function() {} );