import excludeGroupsMather from "../../../select2/excludegroup.matcher";

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
			select.removeAttribute( 'data-select2-id' );

			this.setting.querySelectorAll( '.select2' ).forEach( el => {
				el.remove();
			} );

			jQuery( select ).ac_select2( {
				theme : 'acs2',
				width : '100%',
				dropdownCssClass : '-type-selector',
				escapeMarkup : function( text ) { return text; },
				templateResult : function( result ) {
					let text = result.text;

					if ( result.hasOwnProperty('id') && result.id.includes( 'placeholder-' ) ) {
						text += `<span style="background-color:#FE3D6C; color:#fff; font-size: 10px; margin-top: -1px; padding: 1px 5px; border-radius: 2px; text-transform: uppercase;float: right; margin-right 10px;">PRO</span>`;
					}

					return text;
				},
				matcher : excludeGroupsMather
			} );
		}
	}
}

const type = column => {
	column.settings.typeSelector = new TypeSelector( column );
};

export default type;