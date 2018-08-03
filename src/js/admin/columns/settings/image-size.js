class Image {

	constructor( column ) {
		this.column = column;
		this.setting = column.el.querySelector( '.ac-column-setting--image' );

		if ( ! this.setting ) {
			return;
		}

		this.field = this.setting.querySelector( '.ac-setting-input select' );

		console.log( this );

		this.initState();
		this.bindEvents();
	}

	getValue() {
		return this.field.value;
	}

	bindEvents() {
		let self = this;
		this.field.addEventListener( 'change', function( e ) {
			console.log( self.getValue() );
		} );
	}

	initState() {
		let subsetting = this.setting.querySelector( '.ac-column-setting' );

		if( ! subsetting ){
			return;
		}

		if ( 'cpac-custom' === this.getValue() ) {
			subsetting.style.display = 'table';
		} else {
			subsetting.style.display = 'none';
		}
	}
}

let image = function( column ) {
	column.settings.image = new Image( column );
};

let image2 = function( column ) {
	function initState(  $select ) {
		if ( 'cpac-custom' === $select.val() ) {
			$setting.find( '.ac-column-setting' ).show();
		} else {
			$setting.find( '.ac-column-setting' ).hide();
		}
	}

	column.$el.find( '.ac-column-setting--image' ).each( function() {
		let $setting = $( this );
		let $select = $( this ).find( '.ac-setting-input select' );

		initState( $setting, $select );
		$select.on( 'change', function() {
			initState( $setting, $( this ) );
		} );

	} );
};

module.exports = image;