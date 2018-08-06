import Column from "./column";

class Form {

	constructor( el ) {
		this.$form = jQuery( el );
		this.$container = jQuery( '#cpac .ac-admin' );
		this.columns = {};

		jQuery( document ).trigger( 'AC.form.loaded', this.$container.data( 'type' ) );

		this.init();
	}

	init() {
		this.initColumns();
		this.bindFormEvents();
		this.bindOrdering();
		jQuery( document ).trigger( 'AC.form.ready', this.$container.data( 'type' ) );
	}

	bindOrdering() {

		if ( this.$form.hasClass( 'ui-sortable' ) ) {
			this.$form.sortable( 'refresh' );
		}
		else {
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

	bindFormEvents() {
		let self = this;
		let $buttons = jQuery( '.sidebox a.submit, .column-footer a.submit' );

		$buttons.on( 'click', function() {
			$buttons.attr( 'disabled', 'disabled' );
			self.submitForm().always( function() {
				$buttons.removeAttr( 'disabled', 'disabled' );
			} )
		} );

		self.$container.find( '.add_column' ).on( 'click', function() {
			self.addColumn();
		} );

		let $boxes = jQuery( '#cpac .ac-boxes' );
		if ( $boxes.hasClass( 'disabled' ) ) {

			$boxes.find( '.ac-column' ).each( function( i, col ) {
				jQuery( col ).find( 'input, select' ).prop( 'disabled', true );
			} );
		}

		jQuery( 'a[data-clear-columns]' ).on( 'click', function() {
			self.resetColumns();
		} );
	}

	initColumns() {
		let self = this;
		self.columns = [];

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
		self.columns = [];

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

	submitForm() {
		let self = this;

		let xhr = jQuery.post( ajaxurl, {
				action : 'ac_columns_save',
				data : this.serialize(),
				_ajax_nonce : AC._ajax_nonce,
				list_screen : AC.list_screen,
				layout : AC.layout,
				original_columns : AC.original_columns
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
			// We choose not to notify the user of errors, because the settings will have
			// been saved correctly despite of PHP notices/errors from plugin or themes.
		} );

		jQuery( document ).trigger( 'AC.form.afterUpdate', self.$container );
		return xhr;
	}

	showMessage( message, attr_class = 'updated' ) {
		let $msg = jQuery( '<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>' );

		this.$container.find( '.ac-message' ).stop().remove();
		this.$container.find( '.ac-boxes' ).before( $msg );

		$msg.slideDown();
	}

	cloneColumn( $el ) {
		return this._addColumnToForm( new Column( $el ).clone(), $el.hasClass( 'opened' ) );
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

	_addColumnToForm( column, open = true ) {
		this.columns[ column.name ] = column;
		this.$form.append( column.$el );

		if ( open ) {
			column.open();
		}

		column.$el.hide().slideDown();

		jQuery( 'html, body' ).animate( { scrollTop : column.$el.offset().top - 58 }, 300 );

		jQuery( document ).trigger( 'AC.column.added', column );

		return column;
	}

}

module.exports = Form;