class Column {

	constructor( $el ) {
		this.$el = $el;
		this._name = this.$el.data( 'column-name' );
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

		this._name = name;
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
		let temp_column_name = '_new_column_' + AC.incremental_column_name;
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

	bindEvents() {
		this.$el.column_bind_toggle();
		this.$el.column_bind_remove();
		this.$el.column_bind_clone();
		this.$el.column_bind_events();
		
		this.$el.cpac_bind_indicator_events();
		this.$el.data( 'column', this );

		return this;
	}

	destroy() {
		this.$el.remove();
	}

	toggle( duration = 150 ) {
		this.$el.toggleClass( 'opened' ).find( '.ac-column-body' ).slideToggle( duration );
	}

	open() {
		this.$el.toggleClass( 'opened' ).find( '.ac-column-body' ).show();
	}

	showMessage( message ) {
		//TODO too specific
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
					self.initNewInstance();
					self.bindEvents();
					self.open();
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
					self.initNewInstance();
					self.bindEvents();
					self.open();
				}
			}

		} );
	}
}

module.exports = Column;