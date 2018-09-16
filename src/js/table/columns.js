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
			self._columns[ headers[ i ].id ] = {};
		}
	}

	getColumns() {
		return this._columns;
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

}