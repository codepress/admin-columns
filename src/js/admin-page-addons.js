jQuery( document ).ready( function( $ ) {

	let data = {
		action : 'acp-install-addon',
		plugin_name : 'ac-addon-ninjaforms',
		_ajax_nonce : AC.ajax_nonce
	};

	// todo: fire when "Download & Install" is clicked

	let xhr = $.post( ajaxurl, data, function( response ) {
		console.log( response );
	} );

} );