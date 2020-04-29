import Column from "./column";

class Form {

	constructor( el ) {
		this.form = el;
		this.$form = jQuery( el );
		this.$column_container = this.$form.find( '.ac-columns' );
		this.$container = jQuery( '#cpac .ac-admin' );
		this.columns = {};
		this._validators = [];

		jQuery( document ).trigger( 'AC_Form_Loaded' );

		this.init();
	}

	init() {
		this.initColumns();
		this.bindFormEvents();
		this.bindOrdering();

		if ( this.$form.hasClass( '-disabled' ) ) {
			this.disableFields();
		}

		jQuery( document ).trigger( 'AC_Form_Ready', this );
	}

	bindOrdering() {

		if ( this.$form.hasClass( 'ui-sortable' ) ) {
			this.$form.sortable( 'refresh' );
		} else {
			this.$form.sortable( {
				items : '.ac-column',
				handle : '.column_sort'
			} );
		}

	}

	originalColumns() {
		let self = this;
		let columns = [];

		Object.keys( self.columns ).forEach( function( key ) {
			let column = self.columns[ key ];
			if ( column.isOriginal() ) {
				columns.push( column.type );
			}
		} );

		return columns;
	}

	validateForm() {
		let valid = true;

		this._validators.forEach( validator => {
			valid = validator.call( this, this );
		} );

		return valid;
	}

	addValidator( validator ) {
		this._validators.push( validator );
	}

	bindFormEvents() {
		let self = this;
		let $buttons = jQuery( '.sidebox a.submit, .column-footer a.submit' );

		$buttons.on( 'click', function() {
			if ( !self.validateForm() ) {
				return;
			}
			$buttons.attr( 'disabled', 'disabled' );
			self.$container.addClass( 'saving' );
			self.submitForm().always( function() {
				$buttons.removeAttr( 'disabled', 'disabled' );
				self.$container.removeClass( 'saving' );
			} )
		} );

		self.$container.find( '.add_column' ).on( 'click', function() {
			self.addColumn();
		} );

		let $boxes = jQuery( '#cpac .ac-boxes' );
		if ( $boxes.hasClass( 'disabled' ) ) {
			$boxes.find( '.ac-column' ).each( function( i, col ) {
				jQuery( col ).data( 'column' ).disable();
				jQuery( col ).find( 'input, select' ).prop( 'disabled', true );
			} );
		}

		jQuery( 'a[data-clear-columns]' ).on( 'click', function() {
			self.resetColumns();
		} );
	}

	initColumns() {
		let self = this;
		self.columns = {};

		this.$form.find( '.ac-column' ).each( function() {
			let $el = jQuery( this );
			let column = new Column( $el );

			column.bindEvents();

			$el.data( 'column', column );
			self.columns[ column.name ] = column;
		} );
	}

	reindexColumns() {
		let self = this;
		self.columns = {};

		this.$form.find( '.ac-column' ).each( function() {
			let column = jQuery( this ).data( 'column' );

			self.columns[ column.name ] = column;
		} );
	}

	resetColumns() {
		Object.keys( this.columns ).forEach( ( key ) => {
			let column = this.columns[ key ];

			column.destroy();
		} );

	}

	serialize() {
		return this.$form.serialize();
	}

	disableFields() {
		let form = document.querySelector( this.form );
		if ( !form ) {
			return;
		}

		let elements = form.elements;

		for ( let i = 0; i < elements.length; i++ ) {
			elements[ i ].readOnly = true;
			elements[ i ].setAttribute( 'disabled', true );
		}
	}

	enableFields() {

	}

	submitForm() {
		let self = this;

		let xhr = jQuery.post( ajaxurl, {
				action : 'ac-columns',
				id : 'save',
				_ajax_nonce : AC._ajax_nonce,
				data : this.serialize(),
			},

			function( response ) {
				if ( response ) {
					if ( response.success ) {
						self.showMessage( response.data, 'updated' );

						self.$container.addClass( 'stored' );
					}

					// Error message
					else if ( response.data ) {
						self.showMessage( response.data.message, 'notice notice-warning' );
					}
				}

			}, 'json' );

		// No JSON
		xhr.fail( function( error ) {
			self.showMessage( AC.i18n.errors.save_settings, 'notice notice-warning' );
		} );

		jQuery( document ).trigger( 'AC_Form_AfterUpdate', [self.$container] );

		return xhr;
	}

	showMessage( message, attr_class = 'updated' ) {
		let $msg = jQuery( '<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>' );

		this.$container.find( '.ac-message' ).stop().remove();
		this.$container.find( '.ac-admin__main' ).prepend( $msg );

		$msg.slideDown();
	}

	cloneColumn( $el ) {
		return this._addColumnToForm( new Column( $el ).clone(), $el.hasClass( 'opened' ), $el );
	}

	addColumn() {
		let $clone = jQuery( '#add-new-column-template' ).find( '.ac-column' ).clone();
		let column = new Column( $clone ).create();

		return this._addColumnToForm( column );
	}

	removeColumn( name ) {
		if ( this.columns[ name ] ) {
			this.columns[ name ].remove();
			delete this.columns[ name ];
		}
	}

	getListScreen() {
		return this.$form.find( 'input[name="list_screen"]' ).val();
	}

	getListScreenID() {
		return this.$form.find( 'input[name="list_screen_id"]' ).val();
	}

	getTitle() {
		return this.$form.find( 'input[name="title"]' ).val();
	}

	getColumnSettings() {
		return this.$form.find( '[name^="columns["]' ).serialize();
	}

	_addColumnToForm( column, open = true, $after = null ) {
		this.columns[ column.name ] = column;

		if ( $after ) {
			column.$el.insertAfter( $after );
		} else {
			this.$column_container.append( column.$el );
		}

		if ( open ) {
			column.open();
		}

		column.$el.hide().slideDown();

		jQuery( document ).trigger( 'AC_Column_Added', [column] );

		if ( !isInViewport( column.$el ) ) {
			jQuery( 'html, body' ).animate( { scrollTop : column.$el.offset().top - 58 }, 300 );
		}

		return column;
	}

}

module.exports = Form;

let isInViewport = ( $el ) => {
	var elementTop = $el.offset().top;
	var elementBottom = elementTop + $el.outerHeight();
	var viewportTop = jQuery( window ).scrollTop();
	var viewportBottom = viewportTop + jQuery( window ).height();
	return elementBottom > viewportTop && elementTop < viewportBottom;
};