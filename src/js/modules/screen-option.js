export default class ScreenOption {

	constructor( element, name ) {
		this.name = name;
		this.element = element;
		this.init();
	}

	init() {
		this.element.querySelectorAll( 'input' );
	}

	persist() {
		return jQuery.ajax( {
			url : ajaxurl,
			method : 'POST',
			data : {
				action : 'ac_admin_screen_options',
				option_name : 'test',
				option_value : '1',
				_ajax_nonce: AC._ajax_nonce
			}
		} )
	}

}