/*
 * Fires when the dom is ready
 *
 */
jQuery(document).ready(function()
{	
	cpac_cloning();
	
	// add remove events	
	jQuery( '.column_action .remove-button' ).cpac_remove_button();
});


/*
 * Clone columns
 *
 * @since 2.0.0
 */ 
function cpac_cloning() {
	
	jQuery( '.clone-button' ).click( function(e) {
		
		var type		= jQuery( this ).attr( 'data-type' );				
		var all_columns	= jQuery( this ).closest( '.cpac-boxes' ).find( '.cpac-columns' );		
		var columns 	= jQuery( all_columns ).find( '*[data-type="' + type + '"]' );
		
		if ( columns.length == 0 )
			return;
		
		// get an unique id
		var id 		= 1;
		var ids 	= jQuery.map( columns, function(val, i) { if ( jQuery( val ).attr( 'data-clone' ) ){ return parseInt( jQuery( val ).attr( 'data-clone' ) ); } return 0;	}); // get all data-ids in an array		
		var max_id 	= Math.max.apply( null, ids ) + 1;
		for ( var i=1; i<=max_id; i++ ) {			
			if ( jQuery.inArray( i, ids ) < 0 ) {
				id = i;
			}			
		}
				
		// create a clone and set new id
		var clone = all_columns.find( '.cpac-box-' + type + '[data-clone=""]' ).clone().attr( 'data-clone', id );
		
		// replace column identifier				
		var inputs = jQuery( clone ).find( 'input, select', 'label' );
		jQuery( inputs ).each( function( i, v ) {
			
			var new_name = type + '-' + id;
			
			// name		
			if( jQuery(v).attr( 'name' ) ) {				
				jQuery(v).attr( 'name', jQuery(v).attr( 'name' ).replace( type, new_name) );
			}
			
			// for 
			if( jQuery(v).attr( 'for' ) ) {
				jQuery(v).attr( 'for', jQuery(v).attr( 'for' ).replace( type, new_name ) );
			}
			
			// id
			if( jQuery(v).attr( 'id' ) ) {
				jQuery(v).attr( 'id', jQuery(v).attr( 'id' ).replace( type, new_name ) );
			}			
		});	
		
		
		
		// increment clone
		clone.find( 'input.clone' ).val( id );
		
		// remove description
		clone.find('.remove-description').remove();
		
		// change label text
		clone.find( 'td.column_label a, tr.column_label input.text' ).text( cpac_i18n.customfield );
		clone.find( 'tr.column_label input.text' ).val( cpac_i18n.customfield );
		
		// add remove button	
		if ( clone.find( '.remove-button' ).length == 0 ) {			
			clone.find( 'tr.column_action td.input' ).append( '<p><a href="javascript:;" class="remove-button">' + cpac_i18n.remove + '</a></p>' );
		}
		
		// add click event
		clone.find( '.remove-button' ).cpac_remove_button();		
		
		// add clone to columns
		all_columns.append( clone );
		
		// rebind events
		clone.cpac_form_events();		
		
		// open settings
		clone.slideDown( 150, function() { clone.addClass( 'opened' ); });
		
		// focus on clone
		jQuery('html,body').animate({ scrollTop: clone.offset().top }, 'slow');
		
		e.preventDefault();
	});	
}

/**
 * remove custom field box
 *
 */
jQuery.fn.cpac_remove_button = function() {	

	jQuery( this ).click( function(e) {
		
		var el = jQuery( this ).closest( 'div.cpac-column' );

		el.addClass('deleting').animate({ opacity : 0, height: 0 }, 350, function() { el.remove(); });
		
		e.preventDefault();
	});
}


function _____cpac_cloning() {
	
	jQuery( '.clone-button' ).click( function(e) {
		
		var column_name		= jQuery( this ).attr( 'data-column_name' );
		var all_columns		= jQuery( this ).closest( '.cpac-boxes' ).find( '.cpac-columns' );
		var columns 		= jQuery( all_columns ).find( '.cpac-box-' + column_name );
		
		if ( columns.length == 0 )
			return;
		
		// get an unique id
		var id 		= 0;
		var ids 	= jQuery.map( columns, function(val, i) { return parseInt( jQuery( val ).attr( 'data-id' ) ); }); // get all data-ids in an array
		var max_id 	= Math.max.apply( null, ids ) + 1;
		for ( var i=0; i<=max_id; i++ ) {
			if ( jQuery.inArray( i + "", ids ) < 0 ) {
				id = i;
			}			
		}		
		
		// create a clone
		var clone = all_columns.find( '.cpac-box-' + column_name + '[data-id="0"]' ).clone().attr( 'data-id', id );
		
		// replace column identifier				
		var inputs = jQuery( clone ).find( 'input, select', 'label' );
		jQuery( inputs ).each( function( i, v ) {

			// name		
			if( jQuery(v).attr( 'name' ) ) {				
				jQuery(v).attr( 'name', jQuery(v).attr( 'name' ).replace( '[0]', '[' + id + ']' ) );
			}
			
			// for 
			if( jQuery(v).attr( 'for' ) ) {
				jQuery(v).attr( 'for', jQuery(v).attr( 'for' ).replace( '-0-', '-' + id + '-' ) );
			}
			
			// id
			if( jQuery(v).attr( 'id' ) ) {
				jQuery(v).attr( 'id', jQuery(v).attr( 'id' ).replace( '-0-', '-' + id + '-' ) );
			}			
		});
		
		// remove description
		clone.find('.remove-description').remove();
		
		// change label text
		clone.find( 'td.column_label a, tr.column_label input.text' ).text( cpac_i18n.customfield );
		clone.find( 'tr.column_label input.text' ).val( cpac_i18n.customfield );
		
		// add remove button	
		if ( clone.find('.cpac-remove-column').length == 0 ) {			
			clone.find('tr.column_action td.input').append( '<a href="javascript:;" class="remove-button">' + cpac_i18n.remove + '</a>' ).wrap('<p>');
		}
		
		// add remove event
		clone.find( '.remove-button' ).cpac_remove_button();		
		
		// add clone to columns
		all_columns.append( clone );
		
		// rebind events
		clone.cpac_form_events();		
		
		// open settings
		clone.slideDown( 150, function() { clone.addClass( 'opened' ); });
		
		// focus on clone
		jQuery('html,body').animate({ scrollTop: clone.offset().top }, 'slow');
		
		e.preventDefault();
	});	
}