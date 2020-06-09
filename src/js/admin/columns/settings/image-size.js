class Image {

	constructor( column ) {
		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '.ac-column-setting--image' );

		if ( !this.setting ) {
			return;
		}

		this.field = this.setting.querySelector( '.ac-setting-input select' );

		this.initState();
		this.bindEvents();
	}

	getValue() {
		return this.field.value;
	}

	bindEvents() {
		let self = this;

		this.field.addEventListener( 'change', function( e ) {
			self.initState();
		} );
	}

	initState() {
		if ( 'cpac-custom' === this.getValue() ) {
			this.showSubsettings();
		} else {
			this.hideSubsettings();
		}
	}

	hideSubsettings() {
		let subsetting = this.setting.querySelectorAll( '.ac-column-setting' );

		for ( let i = 0; i < subsetting.length; ++i ) {
			subsetting[ i ].style.display = 'none';
		}

	}

	showSubsettings() {
		let subsetting = this.setting.querySelectorAll( '.ac-column-setting' );

		for ( let i = 0; i < subsetting.length; ++i ) {
			subsetting[ i ].style.display = 'table';
		}
	}

	setValue( value ) {
		this.field.value = value;

		this.trigger( this.field, 'change' );

		return this;
	}

	setWidth( width ) {
		let field = this.setting.querySelector( '.ac-column-setting [name*="image_size_w"]' );

		field.value = width;
		this.trigger( field, 'change' );

		return this;
	}

	setHeight( height ) {
		let field = this.setting.querySelector( '.ac-column-setting [name*="image_size_h"]' );
		field.value = height;

		this.trigger( field, 'change' );

		return this;
	}

	setSize( width, height ) {
		this.setWidth( width );
		this.setHeight( height );

		return this;
	}

	trigger( el, event ) {
		el.dispatchEvent( new Event( event ) );

		return this;
	}

}

let image = function( column ) {
	column.settings.image = new Image( column );
};

export default image;