import fetch from 'node-fetch';
import fs from 'fs/promises';
import path, {dirname} from 'path';
import {fileURLToPath} from 'url';
import {icons} from './icons.js';

const __filename = fileURLToPath( import.meta.url );
const __dirname = dirname( __filename );

const GOOGLE_FONTS_BASE_URL = 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:FILL@0..1';
//const GOOGLE_FONTS_BASE_URL = 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400';

async function downloadFont( icons ) {
	// Construct the Google Fonts URL
	const iconNames = icons.join( ',' );
	const url = `${GOOGLE_FONTS_BASE_URL}&icon_names=${iconNames}&display=block`;

	console.log('URL', url);
	// Fetch the CSS
	const response = await fetch( url );
	const css = await response.text();

	const fontUrlMatch = css.match( /url\((https:\/\/[^)]+)\)/ );
	if ( !fontUrlMatch ) {
		throw new Error( 'Could not find font URL in CSS' );
	}
	const fontUrl = fontUrlMatch[ 1 ];


	console.log( 'Downloading font from:', fontUrl );

	// Download the font
	const fontResponse = await fetch( fontUrl );
	const fontBuffer = await fontResponse.arrayBuffer();

	// Save the font
	const outputDir = path.resolve( __dirname, '../build/fonts' );
	await fs.mkdir( outputDir, { recursive : true } );
	const outputPath = path.join( outputDir, 'material-symbols-outlined.woff2' );
	await fs.writeFile( outputPath, Buffer.from( fontBuffer ) );

	console.log( 'Font saved to:', outputPath );

	// Generate and save CSS
	const cssContent = `@font-face {
  font-family: 'AcMaterialSymbolsOutlined';
  font-style: normal;
  font-weight: 400;
  font-display: block;
  src: url('./material-symbols-outlined.woff2') format('woff2');
}

.ac-material-symbols {
  font-family: 'AcMaterialSymbolsOutlined';
  font-weight: normal;
  font-style: normal;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
}`;

	const cssPath = path.join( outputDir, 'material-symbols-outlined.css' );
	await fs.writeFile( cssPath, cssContent );

	console.log( 'CSS saved to:', cssPath );
}

// List of icons you want to include

icons.sort();

downloadFont( icons ).catch( console.error );