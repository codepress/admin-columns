/*
 * Bind events: triggered by main plugin
 *
 */
jQuery(document).bind('column_init', function( e, column ){
	jQuery(column).column_bind_custom_field_events();
});
jQuery(document).bind('column_change', function( e, clone ){
	jQuery(clone).column_bind_custom_field_events();
});
jQuery(document).bind('column_add', function( e, clone ){
	jQuery(clone).column_bind_custom_field_events();
});

/*
 * Form Events
 *
 * @since 2.0.0
 */
jQuery.fn.column_bind_custom_field_events = function() {

	jQuery(this).find( '.column_field_type .input select' ).change( function() {

		var value = jQuery(this).children(":selected").attr('value');

		// image size
		var image_size = jQuery(this).closest('table').find('.column_image_size').show();
		if( 'image' == value || 'library_id' == value ) {
			image_size.show();
		}
		else {
			image_size.hide();
		}

		// excerpt length
		var excerpt_length = jQuery(this).closest('table').find('.column_excerpt_length').show();
		if( 'excerpt' == value ) {
			excerpt_length.show();
		}
		else {
			excerpt_length.hide();
		}

		// date format
		var date_format = jQuery(this).closest('table').find('.column_date_format').show();
		if( 'date' == value ) {
			date_format.show();
		}
		else {
			date_format.hide();
		}
	});
};