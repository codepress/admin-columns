export default class ScreenOption {

	constructor( element, name ) {
		this.name = name;
		this.element = element;
		this.init();
	}

	getInput() {
		return this.element.querySelector( 'input' );
	}

	init() {
		let input = this.getInput();
		if ( input ) {
			input.addEventListener( 'change', () => {
				this.persist();
			} );
		}
	}

	persist() {
		return jQuery.ajax( {
			url : ajaxurl,
			method : 'POST',
			data : {
				action : 'ac_admin_screen_options',
				option_name : this.name,
				option_value : this.getInput().checked ? 1 : 0,
				_ajax_nonce : AC._ajax_nonce
			}
		} )
	}

}