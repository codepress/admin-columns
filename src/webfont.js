const webfont = require('webfont').default

webfont( {
	files : 'svg/*.svg',
	fontName : 'bla',
	cssTemplateFontPath :'test'
} )
	.then( ( result ) => {
		console.log( result );
	} );