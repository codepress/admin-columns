class NumberFormat {
	constructor( column ) {
		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '.ac-column-setting--number_format' );

		if ( !this.setting ) {
			return;
		}

		this.bindEvents();
	}

	bindEvents() {
		this.refreshPreview();
		this.setting.querySelectorAll( 'input' ).forEach( el => {
			el.addEventListener( 'change', ( e ) => {
				this.refreshPreview();
			} )
		} )
	}

	refreshPreview() {
		this.getExampleRequest().done( ( example ) => {
			let preview = this.setting.querySelector( '[data-preview]' );

			if ( preview ) {
				preview.innerText = example;
			}
		} );
	}

	getExampleRequest() {
		let decimals = this.setting.querySelector( '.ac-setting-input_number_decimals' );
		let decimal_point = this.setting.querySelector( '.ac-setting-input_number_decimal_point' );
		let thousands_point = this.setting.querySelector( '.ac-setting-input_number_thousands_separator' );

		return jQuery.ajax( {
			url : ajaxurl,
			method : 'post',
			data : {
				action : 'ac_number_format',
				number : 7500,
				decimals : decimals ? decimals.value : '',
				decimal_point : decimal_point ? decimal_point.value : '',
				thousands_point : thousands_point ? thousands_point.value : '',
			}
		} );
	}
}

const numberformat = column => {
	column.settings.number_format = new NumberFormat( column );
};

export default numberformat;