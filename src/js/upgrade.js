/**
 * Add Message
 */
function add_message( message ) {
	jQuery('#wpbody-content').append('<p>' + message + '</p>');
}

/**
 * Run upgrade process
 *
 */
function run_upgrade( version ) {

	jQuery.ajax({
		url: ajaxurl,
		data: {
			action : 'cpac_upgrade',
			version : version
		},
		type: 'post',
		dataType: 'json',
		success: function( json ){

			if( json ) {
				if( json.status ) {

					add_message( json.message );

					// next update?
					if( json.next ) {
						run_upgrade( json.next );
					}

					// all done
					else {
						add_message( cpac_upgrade_i18n.complete );
					}
				}

				// error!
				else {
					add_message( cpac_upgrade_i18n.error + ': ' + json.message );
				}
			}

			// major error!
			else {

				add_message( cpac_upgrade_i18n.major_error );
			}
		}
	});
};