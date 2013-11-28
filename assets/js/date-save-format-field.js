
/*
 * Bind events: triggered by main plugin
 *
 */
jQuery(document).bind('column_init', function( e, column ){
	jQuery(column).column_bind_date_save_formats_events();
});
jQuery(document).bind('column_change', function( e, clone ){
	jQuery(clone).column_bind_date_save_formats_events();
});
jQuery(document).bind('column_add', function( e, clone ){
	jQuery(clone).column_bind_date_save_formats_events();
});

/*
 * Form Events
 *
 * @since 2.0.0
 */
jQuery.fn.column_bind_date_save_formats_events = function() {

	jQuery(this).find( '.column_field_type .input select' ).change( function() {

		var value = jQuery(this).children(":selected").attr('value');

		// date format
		var date_save_format = jQuery(this).closest('table').find('.column_date_save_format');

		if ( date_save_format.length > 0 ) {

			if( 'date' == value ) {
				date_save_format.show();
			}
			else {
				date_save_format.hide();
			}
		}
	});
};