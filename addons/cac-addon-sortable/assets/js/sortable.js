/*
 * Fires when the dom is ready
 *
 */
jQuery(document).ready(function()
{	
	// set sorting state
	jQuery( '.column_sorting .input label' ).click( function(){
		
		var val = jQuery( 'input', this ).val();
		
		if ( 'on' == val ) {			
			jQuery( this ).closest( '.cpac-column' ).find( '.column-meta .column_label .sorting' ).addClass( 'on' );
		} else {
			jQuery( this ).closest( '.cpac-column' ).find( '.column-meta .column_label .sorting' ).removeClass( 'on' );
		}		
	});
	
});