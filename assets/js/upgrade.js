/**
 * Add Message
 */
function add_message( message ) {
	jQuery('#wpbody-content').append('<p>' + message + '</p>');
}

/**
 * Run upgrade process
 *
 * @todo: localize script
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
						add_message( 'Upgrade Complete!' );
					}
				}

				// error!
				else {
					add_message( 'Error: ' + json.message );
				}
			}

			// major error!
			else {

				add_message( 'Sorry. Something went wrong during the upgrade process. Please report this on the support forum' );
			}
		}
	});
};