class TypeSelector {
	constructor( column ) {
		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '[data-setting="type"]' );

		if ( !this.setting ) {
			return;
		}

		this.bindEvents();
	}

	bindEvents() {
		const select = this.setting.querySelector( '.ac-setting-input_type' );

		if ( select ) {
			jQuery( select ).ac_select2( {
				theme : 'acs2',
				width : '100%',
				dropdownCssClass : '-type-selector',
			} );
		}
	}
}

const type = column => {
	column.settings.typeSelector = new TypeSelector( column );
};

export default type;