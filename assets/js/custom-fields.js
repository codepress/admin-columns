/*
 * Fires when the dom is ready
 *
 */
jQuery(document).ready(function()
{
	cpac_add_custom_column();
});

/*
 * Add custom columns
 *
 * @since 1.5
 */
function cpac_add_custom_column()
{
	jQuery('.add-customfield-column').click(function(e){
		e.preventDefault();

		var list 		= jQuery(this).closest('.cpac-boxes').find('.cpac-columns');
		var metafields 	= jQuery('.cpac-box-metafield', list);

		// get unique ID number...
		var ids = [];
		metafields.each(function(k,v) {
			var classes = jQuery(v).attr('class').split(' ');

			jQuery.each(classes, function(kc,vc){
				if ( vc.indexOf('cpac-box-column-meta-') === 0 ) {
					var id = vc.replace('cpac-box-column-meta-','');
					if ( id ) {
						ids.push(id);
					}
				}
			});
		});

		// ...and sort them
		ids.sort(sortNumber);

		if ( !ids )
			return;

		function sortNumber(a,b) {
			return b - a;
		}

		// ID's
		var id 		= parseFloat(ids[0]);
		var new_id 	= id + 1;

		// Clone
		var clone = jQuery( '.cpac-box-column-meta-' + id, list ).clone();

		// Toggle class
		jQuery(clone).removeClass('cpac-box-column-meta-' + id );
		jQuery(clone).addClass('cpac-box-column-meta-' + new_id );

		// Replace inputs ID's
		var inputs = jQuery(clone).find('input, select');
		jQuery(inputs).each(function(ik, iv){
			jQuery(iv).attr('name', jQuery(iv).attr('name').replace(id, new_id) );
			jQuery(iv).attr('id', jQuery(iv).attr('id').replace(id, new_id) );
		});

		// Replace label ID's
		var labels = jQuery(clone).find('label');
		jQuery(labels).each(function(ik, iv){
			var attr_for = jQuery(iv).attr('for');
			if ( attr_for ) {
				jQuery(iv).attr('for', attr_for.replace(id, new_id) );
			}
		});

		// remove description
		clone.find('.remove-description').remove();

		// change label text
		clone.find('td.column_label a, tr.column_label input.text').text(cpac_i18n.customfield);
		clone.find('tr.column_label input.text').val(cpac_i18n.customfield);

		// add remove button
		if ( clone.find('.cpac-delete-custom-field-box').length == 0 ) {
			var remove = '<p><a href="javascript:;" class="cpac-delete-custom-field-box button">' + cpac_i18n.remove + '</a>';
			clone.find('tr.column_action td.input').append(remove);
		}

		// add cloned box to the list
		list.append(clone);

		// retrigger events
		cpac_box_events();

		// open
		open_form( clone );

		// focus on clone
		jQuery('html,body').animate({ scrollTop: clone.offset().top }, 'slow');
	});
}