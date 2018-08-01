class Tooltips {

	constructor() {
		this.isEnabled = typeof jQuery.fn.qtip !== 'undefined';

		this.init();
	}

	init() {
		if ( !this.isEnabled ) {
			console.log( 'Tooltips not loaded!' );
			return;
		}

		jQuery( '[data-ac-tip]' ).qtip( {
			content : {
				attr : 'data-ac-tip'
			},
			position : {
				my : 'top center',
				at : 'bottom center'
			},
			style : {
				tip : true,
				classes : 'qtip-tipsy'
			}
		} );
	}

}

module.exports = Tooltips;