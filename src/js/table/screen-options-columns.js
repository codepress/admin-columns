export default class ScreenOptionsColumns {

	constructor( columns ) {
		this.columns = columns;

		columns.getColumnNames().forEach( ( column_name ) => {
			let column = columns.get( column_name );
			let input = ScreenOptionsColumns.getInputByName( column.name );

			if ( input && input.parentElement.textContent.length === 0 ) {
				let label = document.createElement( 'span' );
				label.innerHTML = column.label;
				input.parentElement.appendChild( label );
			}
		} );
	}

	static getInputByName( name ) {
		let input = document.querySelector( `input[name='${name}-hide']` );

		return input ? input : false;
	}

}