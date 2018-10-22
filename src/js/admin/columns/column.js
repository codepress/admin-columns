class Column {

	constructor( $el ) {
		this.$el = $el;
		this.el = $el[ 0 ];
		this.settings = [];

		this._type = this.$el.data( 'type' );
	}

	get name() {
		return this.$el.data( 'column-name' );
	}

	set name( name ) {
		this.$el.data( 'column-name', name );
	}

	get type() {
		return this._type;
	}

	set type( type ) {
		this.$el.data( 'type', type );
	}

	isOriginal() {
		return (1 === this.$el.data( 'original' ));
	}

	isDisabled() {
		return this.$el.hasClass( 'disabled' );
	}

	disable() {
		this.$el.addClass( 'disabled' );

		return this;
	}

	enable() {
		this.$el.removeClass( 'disabled' );

		return this;
	}

	initNewInstance() {
		let temp_column_name = '_new_column_' + AC.Column.getNewIncementalName();
		let original_column_name = this.name;

		this.$el.find( 'input, select, label' ).each( function( i, v ) {
			let $input = jQuery( v );

			// name attributes
			if ( $input.attr( 'name' ) ) {
				$input.attr( 'name', $input.attr( 'name' ).replace( `columns[${original_column_name}]`, `columns[${temp_column_name}]` ) );
			}

			// id attributes
			if ( $input.attr( 'id' ) ) {
				$input.attr( 'id', $input.attr( 'id' ).replace( `-${original_column_name}-`, `-${temp_column_name}-` ) );
			}

		} );

		this.name = temp_column_name;

		AC.incremental_column_name++;

		return this;
	}

	/**
	 *
	 * @returns {Column}
	 */
	bindEvents() {
		let column = this;
		column.$el.data( 'column', column );

		Object.keys( AC.Column.events ).forEach( function( key ) {
			if ( !column.isBound( key ) ) {
				AC.Column.events[ key ]( column );
				column.bind( key );
			}
		} );

		this.bindSettings();

		jQuery( document ).trigger( 'AC_Column_InitSettings', [ column ] );

		return this;
	}

	bindSettings() {
		let column = this;

		Object.keys( AC.Column.settings ).forEach( function( key ) {
			if ( !column.isBound( key ) ) {
				AC.Column.settings[ key ]( column );
				column.bind( key );
			}
		} );
	}

	/**
	 *
	 * @param key
	 * @returns {bool}
	 */
	isBound( key ) {
		return this.$el.data( key );
	}

	bind( key ) {
		this.$el.data( key, true );
	}

	destroy() {
		this.$el.remove();
	}

	remove( duration = 350 ) {
		let self = this;

		this.$el.addClass( 'deleting' ).animate( { opacity : 0, height : 0 }, duration, function() {
			self.destroy();
		} );
	}

	toggle( duration = 150 ) {
		if ( this.$el.hasClass( 'opened' ) ) {
			this.close( duration );
		} else {
			this.open( duration );
		}
	}

	close( duration = 0 ) {
		this.$el.removeClass( 'opened' ).find( '.ac-column-body' ).slideUp( duration );
	}

	open( duration = 0 ) {
		this.$el.addClass( 'opened' ).find( '.ac-column-body' ).slideDown( duration );
	}

	showMessage( message ) {
		this.$el.find( '.ac-column-setting--type .msg' ).html( message ).show();
	}

	switchToType( type ) {
		let self = this;

		return jQuery.ajax( {
			url : ajaxurl,
			method : 'post',
			dataType : 'json',
			data : {
				action : 'ac_column_select',
				type : type,
				current_original_columns : AC.Form.originalColumns(),
				original_columns : AC.original_columns,
				list_screen : AC.list_screen,
				layout : AC.layout,
				_ajax_nonce : AC._ajax_nonce,
			},
			success : function( response ) {
				if ( true === response.success ) {
					let column = jQuery( response.data );

					self.$el.replaceWith( column );
					self.$el = column;
					self.el = column[ 0 ];
					self._type = type;
					self.initNewInstance();
					self.bindEvents();
					self.open();

					jQuery( document ).trigger( 'AC_Column_Change', [ self ] );
				} else {
					self.showMessage( response.data.error )
				}
			}
		} );
	}

	refresh() {

		let self = this;
		let data = this.$el.find( ':input' ).serializeArray();
		let request_data = {
			action : 'ac_column_refresh',
			_ajax_nonce : AC._ajax_nonce,
			list_screen : AC.list_screen,
			layout : AC.layout,
			column_name : this.name,
			original_columns : AC.original_columns
		};

		jQuery.each( request_data, function( name, value ) {
			data.push( {
				name : name,
				value : value
			} );
		} );

		return jQuery.ajax( {
			type : 'post',
			url : ajaxurl,
			data : data,

			success : function( response ) {
				if ( true === response.success ) {
					let column = jQuery( response.data );

					self.$el.replaceWith( column );
					self.$el = column;
					self.el = column[ 0 ];

					self.bindEvents();
					self.open();

					jQuery( document ).trigger( 'AC_Column_Refresh', [ self ] );
				}
			}

		} );
	}

	/**
	 * @returns {Column}
	 */
	create() {
		this.initNewInstance();
		this.bindEvents();

		jQuery( document ).trigger( 'AC_Column_Created', [ self ] );
		return this;
	}

	/**
	 * @returns {Column}
	 */
	clone() {
		let $clone = this.$el.clone();
		$clone.data( 'column-name', this.$el.data( 'column-name' ) );

		let clone = new Column( $clone );

		clone.initNewInstance();
		clone.bindEvents();

		return clone;
	}
}

module.exports = Column;