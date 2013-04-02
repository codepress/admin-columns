/*
 * Fires when the dom is ready
 *
 */
jQuery(document).ready(function()
{
	//cpac_cloning();

	// add remove events
	//jQuery( '.column_action .remove-button' ).cpac_remove_button();
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
		var columns		= jQuery( all_columns ).find( '*[data-type="' + type + '"]' );

		if ( columns.length === 0 )
			return;

		// get an unique id
		var id		= 1;
		var ids		= jQuery.map( columns, function(val, i) { if ( jQuery( val ).attr( 'data-clone' ) ){ return parseInt( jQuery( val ).attr( 'data-clone' ) ); } return 0;	}); // get all data-ids in an array
		var max_id 	= Math.max.apply( null, ids ) + 1;
		for ( var i=1; i<=max_id; i++ ) {
			if ( jQuery.inArray( i, ids ) < 0 ) {
				id = i;
			}
		}

		// create a clone and set new id
		var clone = all_columns.find( '.cpac-box-' + type + '[data-clone=""]' ).clone().attr( 'data-clone', id );

		// replace column identifier
		var inputs = jQuery( clone ).find( 'input, select, label' );
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
		var label = jQuery( '.column-meta .column_type', clone ).text().trim();
		clone.find( 'td.column_label a, tr.column_label input.text' ).text( label );
		clone.find( 'tr.column_label input.text' ).val( label );

		// add remove button
		if ( clone.find( 'tr.column_action td .remove-button' ).length === 0 ) {
			clone.find( 'tr.column_action td' ).append( '<p><a href="javascript:;" class="remove-button">' + cpac_i18n.remove + '</a></p>' );
		}

		// add click event
		clone.find( '.remove-button' ).cpac_remove_button();

		// rebind events
		clone.cpac_form_events();

		// add clone to columns
		all_columns.append( clone );

		// open settings
		jQuery( '.column-form', clone ).slideDown( 150, function() { clone.addClass( 'opened' ); });

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

	jQuery(this).click( function(e) {

		var el = jQuery( this ).closest( 'div.cpac-column' );

		el.addClass('deleting').animate({ opacity : 0, height: 0 }, 350, function() { el.remove(); });

		e.preventDefault();
	});
}