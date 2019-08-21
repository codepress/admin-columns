var nanobus = require( 'nanobus' );

class SingleCustomFieldRequestManager {

	constructor( meta_type, post_type ) {
		this.meta_type = meta_type;
		this.post_type = post_type;
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
				post_type : this.post_type,
				meta_type : this.meta_type,
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

const loadSingleRequestManager = ( meta_type, post_type ) => {
	const key = `custom_field_${meta_type}_${post_type}`;

	if ( typeof AC_Requests === 'undefined' ) {
		global.AC_Requests = {};
	}

	if ( !AC_Requests.hasOwnProperty( key ) ) {
		AC_Requests[ key ] = new SingleCustomFieldRequestManager( meta_type, post_type );
	}

	return AC_Requests[ key ];
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
		const input = this.setting.querySelector( '.custom_field' );
		const request = loadSingleRequestManager( input.dataset.type, input.dataset.post_type );
		const editingAvailable = this.column.el.querySelectorAll( '[data-setting="edit"][data-indicator-toggle]' ).length > 0;

		this.setting.querySelectorAll( '.select2' ).forEach( el => {
			el.remove();
		} );

		request.getOptions().done( data => {
			jQuery( input ).ac_select2( {
				theme : 'acs2',
				width : '100%',
				tags : editingAvailable,
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