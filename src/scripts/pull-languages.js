// Downloads the .po files from translate.wordpress.org and compiles the .mo files locally.
// A file is only replaced after it has been validated, so a failed download keeps the previous translation.

const fs = require( 'fs' );
const os = require( 'os' );
const path = require( 'path' );
const { spawnSync } = require( 'child_process' );

const LANGUAGES_DIR = path.resolve( __dirname, '../../languages' );
const DOMAIN = 'codepress-admin-columns';
const URL = 'https://translate.wordpress.org/projects/wp-plugins/codepress-admin-columns/stable/%s/default/export-translations/?format=po';

const DELAY_MS = 1500;
const ATTEMPTS = 4;
const TIMEOUT_MS = 30000;

// translate.wordpress.org slug => WordPress locale
const LANGUAGES = {
	'ar' : 'ar',
	'bg' : 'bg_BG',
	'da' : 'da_DK',
	'de' : 'de_DE',
	'es' : 'es_ES',
	'en-nz' : 'en_NZ',
	'fa' : 'fa_IR',
	'fr' : 'fr_FR',
	'he' : 'he_IL',
	'hu' : 'hu_HU',
	'it' : 'it_IT',
	'ja' : 'ja',
	'nl' : 'nl_NL',
	'nb' : 'nb_NO',
	'pl' : 'pl_PL',
	'pt-br' : 'pt_BR',
	'pt' : 'pt_PT',
	'ro' : 'ro_RO',
	'ru' : 'ru_RU',
	'sv' : 'sv_SE',
	'tr' : 'tr_TR',
	'zh-cn' : 'zh_CN',
};

const sleep = ms => new Promise( resolve => setTimeout( resolve, ms ) );

const retryDelay = ( response, attempt ) => {
	const retryAfter = response ? parseInt( response.headers.get( 'retry-after' ), 10 ) : NaN;

	return Number.isNaN( retryAfter ) ? attempt * 5000 : retryAfter * 1000;
};

async function download( url ) {
	let error;

	for ( let attempt = 1; attempt <= ATTEMPTS; attempt++ ) {
		let response = null;

		try {
			response = await fetch( url, { signal : AbortSignal.timeout( TIMEOUT_MS ) } );

			if ( response.ok ) {
				return await response.text();
			}

			error = `HTTP ${response.status}`;

			if ( response.status !== 429 && response.status < 500 ) {
				break;
			}
		} catch ( e ) {
			error = e.message;
		}

		if ( attempt < ATTEMPTS ) {
			const delay = retryDelay( response, attempt );
			console.log( `    ${error}, retry ${attempt}/${ATTEMPTS - 1} in ${delay / 1000}s` );
			await sleep( delay );
		}
	}

	throw new Error( error );
}

function validate( content ) {
	if ( /<html|<!doctype/i.test( content ) ) {
		throw new Error( 'received an HTML page instead of a .po file' );
	}

	if ( !content.includes( 'msgid ""' ) ) {
		throw new Error( 'no .po header found' );
	}
}

function compile( poFile, moFile ) {
	const result = spawnSync( 'msgfmt', [ '--check', '-o', moFile, poFile ], { encoding : 'utf8' } );

	if ( result.status !== 0 ) {
		throw new Error( `msgfmt failed: ${( result.stderr || '' ).trim().split( '\n' )[ 0 ]}` );
	}
}

async function pull( slug, locale, tmpDir ) {
	const base = `${DOMAIN}-${locale}`;
	const tmpPo = path.join( tmpDir, `${base}.po` );
	const tmpMo = path.join( tmpDir, `${base}.mo` );

	const content = await download( URL.replace( '%s', slug ) );

	validate( content );
	fs.writeFileSync( tmpPo, content );
	compile( tmpPo, tmpMo );

	fs.copyFileSync( tmpPo, path.join( LANGUAGES_DIR, `${base}.po` ) );
	fs.copyFileSync( tmpMo, path.join( LANGUAGES_DIR, `${base}.mo` ) );
}

function unknownFiles() {
	const known = new Set( Object.values( LANGUAGES ).flatMap( locale => [ 'po', 'mo' ].map( ext => `${DOMAIN}-${locale}.${ext}` ) ) );

	return fs.readdirSync( LANGUAGES_DIR ).filter( file => /\.(po|mo)$/.test( file ) && !known.has( file ) );
}

async function main() {
	if ( spawnSync( 'msgfmt', [ '--version' ] ).error ) {
		console.error( 'msgfmt not found. Install gettext first (brew install gettext).' );
		process.exit( 2 );
	}

	const tmpDir = fs.mkdtempSync( path.join( os.tmpdir(), 'ac-languages-' ) );
	const failed = [];
	const entries = Object.entries( LANGUAGES );

	console.log( `Downloading ${entries.length} languages from translate.wordpress.org` );

	try {
		for ( const [ index, [ slug, locale ] ] of entries.entries() ) {
			if ( index > 0 ) {
				await sleep( DELAY_MS );
			}

			try {
				await pull( slug, locale, tmpDir );
				console.log( `  ✓ ${locale}` );
			} catch ( e ) {
				failed.push( `${locale} (${e.message})` );
				console.log( `  ✗ ${locale}: ${e.message}` );
			}
		}
	} finally {
		fs.rmSync( tmpDir, { recursive : true, force : true } );
	}

	const unknown = unknownFiles();

	if ( unknown.length ) {
		console.warn( `\nNot in the language list, remove or add them: ${unknown.join( ', ' )}` );
	}

	console.log( `\n${entries.length - failed.length} updated, ${failed.length} failed` );

	if ( failed.length ) {
		console.error( `Failed, previous files kept:\n  ${failed.join( '\n  ' )}` );
		process.exit( 1 );
	}
}

main();
