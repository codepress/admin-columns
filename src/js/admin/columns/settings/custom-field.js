var nanobus = require( 'nanobus' );

class SingleCustomFieldRequestManager {

	constructor() {
		this.loading = false;
		this.data = false;
		this.events = nanobus();
	}

	retrieveOptions() {
		this.loading = true;

		return jQuery.ajax( {
			url : ajaxurl,
			dataType : 'json',
			method : 'post',
			data : {
				action : 'ac_custom_field_options',
				layout : AC.layout,
				list_screen : AC.list_screen,
				_ajax_nonce : AC._ajax_nonce
			}
		} );
	}

	getOptions() {
		const defer = jQuery.Deferred();

		if ( this.data ) {
			defer.resolve( this.data );
		} else if ( this.loading ) {
			this.events.on( 'loaded', () => {
				defer.resolve( this.data );
			} )
		} else {
			this.retrieveOptions().done( response => {
				if ( !response.success ) {
					defer.reject();
				}

				this.data = response.data.results;
				this.events.emit( 'loaded' );

				defer.resolve( this.data );
			} );
		}

		return defer.promise();
	}

}

const loadSingleRequestManager = () => {
	if ( !AdminColumns.hasOwnProperty( 'SingleCustomFieldRequest' ) ) {
		AdminColumns.SingleCustomFieldRequest = new SingleCustomFieldRequestManager();
	}

	return AdminColumns.SingleCustomFieldRequest;
};

class CustomField {
	constructor( column ) {
		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '.ac-column-setting--custom_field' );

		if ( !this.setting ) {
			return;
		}

		this.bindEvents();
	}

	bindEvents() {
		const request = loadSingleRequestManager();
		const input = this.setting.querySelector( '.custom_field' );

		request.getOptions().done( data => {
			jQuery( input ).find( 'option' ).remove();
			jQuery( input ).ac_select2( {
				theme : 'acs2',
				width : '100%',
				tags : true,
				dropdownCssClass : '-customfields',
				data : data
			} );
		} );
	}
}

const customfield = column => {
	column.settings.customfield = new CustomField( column );
};

export default customfield;