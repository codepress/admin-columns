export default function excludeGroupsMather( params, data ) {
	if ( jQuery.trim( params.term ) === '' ) {
		return data;
	}

	if ( typeof data.children === 'undefined' ) {
		return null;
	}

	var filteredChildren = [];
	jQuery.each( data.children, function( idx, child ) {
		if ( child.text.toUpperCase().indexOf( params.term.toUpperCase() ) > -1 ) {
			filteredChildren.push( child );
		}
	} );

	if ( filteredChildren.length ) {
		var modifiedData = jQuery.extend( {}, data, true );
		modifiedData.children = filteredChildren;

		return modifiedData;
	}

	return null;
}