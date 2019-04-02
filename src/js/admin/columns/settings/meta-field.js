class CustomField {
	constructor( column ) {
		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '.ac-column-setting--custom_field' );

		if ( !this.setting ) {
			return;
		}

		this.bindEvents();
	}

	getPreloadedOptions( ) {
		return jQuery.ajax( {
			url : ajaxurl,
			dataType : 'json',
			method : 'post',
			data : {
				action : 'ac_get_custom_fields_options',
				layout : AC.layout,
				column : this.column.name,
				list_screen : AC.list_screen,
				_ajax_nonce : AC.ajax_nonce
			}
		} );
	}

	bindEvents() {
		this.getPreloadedOptions().done( d => {
			console.log( d, 'sdf' );
		});
		const input = this.setting.querySelector( '.custom_field' );

		jQuery( input ).ac_select2( {
			theme : 'acs2',
			width : '100%',
			tags : true,
			data : [
				'test', 'HOI'
			]
		} );
	}
}

const customfield = column => {
	column.settings.customfield = new CustomField( column );
};

export default customfield;