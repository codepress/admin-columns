class NumberFormat {
	constructor( column ) {
		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '.ac-column-setting--number_format' );

		if ( !this.setting ) {
			return;
		}

		this.preview = new Preview( this.setting.querySelector( '[data-preview]' ) );
		this.bindEvents();
	}

	bindEvents() {
		this.refreshPreview();
		this.setting.querySelectorAll( 'input,select' ).forEach( el => {
			el.addEventListener( 'change', () => {
				this.refreshPreview();
			} )
		} )
	}

	refreshPreview(){
		let inp_decimals = this.setting.querySelector( '.ac-setting-input_number_decimals' );
		if( inp_decimals ){
			this.preview.decimals = inp_decimals.value;
		}

		let inp_decimal_separator = this.setting.querySelector( '.ac-setting-input_number_decimal_point' );
		if( inp_decimal_separator ){
			this.preview.decimal_separator = inp_decimal_separator.value;
		}

		let inp_thousand_point = this.setting.querySelector( '.ac-setting-input_number_thousands_separator' );
		if( inp_thousand_point ){
			this.preview.thousand_point = inp_thousand_point.value;
		}

		this.preview.refresh();
	}
}

class Preview {
	constructor( el ) {
		this.el = el;
		this.decimals = 0;
		this.decimal_separator = ',';
		this.thousand_point = '.';
		this.template = `7<span data-bind="ts"></span>500<span data-bind="ds"></span><span data-bind="decimals"></span>`;
	}

	refresh() {
		if( ! this.el ){
			return;
		}

		if( this.decimals > 20 ){
			return 20;
		}
		this.el.innerHTML = this.template;
		this.el.querySelectorAll( '[data-bind=ts]' ).forEach( el => {
			el.innerText = this.thousand_point;
		} );

		let decimal_sep = this.decimal_separator;
		if( this.decimals === 0 ){
			decimal_sep = '';
		}
		this.el.querySelectorAll( '[data-bind=ds]' ).forEach( el => {
			el.innerText = decimal_sep;
		} );

		let _decimals = '';
		for ( let i = 0; i < this.decimals; i++ ) {
			_decimals = `0${_decimals}`;
		}

		this.el.querySelectorAll( '[data-bind=decimals]' ).forEach( el => {
			el.innerText = _decimals;
		} );
	}

}

const numberformat = column => {
	column.settings.number_format = new NumberFormat( column );
};

export default numberformat;