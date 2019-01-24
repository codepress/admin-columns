export default class Columns {

	constructor( table ) {
		this.table = table;
		this._columns = {};

		this.init();
	}

	init() {
		let self = this;
		let thead = this.table.querySelector( 'thead' );
		let headers = thead.querySelectorAll( 'th' );

		for ( let i = 0; i < headers.length; i++ ) {
			let column = {};
			column.name = headers[ i ].id;
			column.type = AC.column_types[ column.name ];
			column.label = this.sanitizeLabel( headers[ i ] );
			self._columns[ headers[ i ].id ] = column;
		}
	}

	getColumns() {
		return this._columns;
	}

	getColumnsMap() {
		let map = new Map();
		let columns = this.getColumns();

		Object.keys( columns ).forEach( ( k ) => {
			map.set( k, columns[ k ] )
		} );

		return map;
	}

	/**
	 * @returns {string[]}
	 */
	getColumnNames() {
		return Object.keys( this._columns );
	}

	/**
	 *
	 * @param {String} column_name
	 * @returns {Object}
	 */
	get( column_name ) {
		if ( !this._columns[ column_name ] ) {
			return false;
		}

		return this._columns[ column_name ];
	}

	sanitizeLabel( header ) {
		let link = header.querySelector( 'a' );
		let label = header.innerHTML;

		// If it contains a link, we presume that it is because of sorting
		if ( link ) {
			let elements = link.getElementsByTagName( 'span' );

			if ( elements.length > 0 ) {
				label = elements[ 0 ].innerHTML;
			}
		}

		return label;
	}
}