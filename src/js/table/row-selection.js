export default class Selection {

	constructor( Table ) {
		this.Table = Table;
	}

	/**
	 * Get the selected IDs in the table
	 *
	 * @returns {Array}
	 */
	getIDs() {
		let ids = [];
		let checked = this.Table.el.querySelectorAll( 'tbody th.check-column input[type=checkbox]:checked' );

		if ( checked.length === 0 ) {
			return ids;
		}

		for ( let i = 0; i < checked.length; i++ ) {
			ids.push( checked[ i ].value );
		}

		return ids;
	}

	/**
	 * Get selected cells for specific column
	 *
	 * @param name
	 */
	getSelectedCells( name ) {
		let self = this;
		let ids = this.getIDs();

		if ( ids.length === 0 ) {
			return false;
		}

		let cells = [];

		ids.forEach( ( id ) => {
			let cell = self.table.Cells.get( id, name );

			if ( cell ) {
				cells.push( cell );
			}
		} );

		return cells;
	}

	/**
	 *
	 * @returns {number}
	 */
	getCount() {
		return this.getIDs().length;
	}

	isAllSelected(){
		return !!this.Table.el.querySelector('thead #cb input:checked');
	}

}