const webfont = require( "webfont" ).default;
const fs = require( 'fs' )
const path = require( 'path' )

const templatePath = 'webfont/scss/template.scss';
const fontPath = 'webfont/fonts/';

webfont( {
	files : "svg/**/*.svg",
	template : "webfont/template.scss.njk",
	templateFontPath : '../fonts/',
	fontName : "cpacicon",
	formats : ['woff2']
} )
	.then( async result => {
		const { fontName } = result.config;

		Object.keys( result ).map( async type => {
			if ( type === 'config' || type === "glyphsData" ) {
				return null;
			}

			const file = (type === 'template')
				? templatePath
				: fontPath + `${fontName}.${type}`

			await fs.writeFile( file, result[ type ], () => true )
		} );

		await sleep(100);
		copyFonts();
		copyScss();

		return result;
	} )
	.catch( error => {
		throw error;
	} );

const copyFonts = async () => {
	const assetsFontPath = '../assets/fonts/';

	fs.readdir( fontPath, ( err, files ) => {
		files.forEach( file => {
			let fullPath = path.join( fontPath, file );
			let newFilename = assetsFontPath + file;
			fs.copyFile( fullPath, newFilename, ( e ) => {
				if ( e ) {
					throw e
				}
				console.log( 'Fonts copied' );
			} );
		} );
	} );
}

const copyScss = () => {
	fs.copyFile( templatePath, 'scss/_webfont.scss', ( e ) => {
		if ( e ) {
			throw e
		}
		console.log( 'Sass file copied' );
	} );
}

const sleep = ( milliseconds ) => {
	return new Promise( resolve => setTimeout( resolve, milliseconds ) )
}