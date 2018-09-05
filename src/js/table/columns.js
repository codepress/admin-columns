export default class Columns {

	constructor( table ) {
		this.table = table;
		this._columns = new Map();
		this._types = {};

		this.init();
	}

	init() {
		this._initColumnTypes();
	}

	_initColumnTypes() {
		let self = this;
		let thead = this.table.querySelector( 'thead' );
		let headers = thead.querySelectorAll( 'th' );

		for ( let i = 0; i < headers.length; i++ ) {
			self._types[ headers[ i ].id ] = {};
		}
	}

	addColumn( id, column ) {
		if ( !this._columns.has( id ) ) {
			this._columns.set( id, new Map() );
		}

		this._columns.get( id ).set( column.getName(), column );
	}

	getColumnTypes() {
		return Object.keys( this._types );
	}

	getColumnSettings( column_type ) {
		if ( !this._types[ column_type ] ) {
			return false;
		}

		return this._types[ column_type ];
	}

	getByID( id ) {
		let result = [];
		let key = id.toString();
		if ( !this._columns.has( key ) ) {
			return result;
		}

		this._columns.get( id.toString() ).forEach( function( column ) {
			result.push( column );
		} );

		return result;
	}

	getAll() {
		let results = [];

		this._columns.forEach( function( columns ) {

			columns.forEach( function( column ) {
				results.push( column );
			} )

		} );

		return results;
	}

	getByType( type ) {
		let results = [];

		this._columns.forEach( function( columns ) {

			columns.forEach( function( column, name ) {
				if ( name === type ) {
					results.push( column );
				}
			} );

		} );

		return results;
	}

	get( id, type ) {
		let row = this._columns.get( id.toString() );

		if ( !row ) {
			return false;
		}

		return row.get( type );
	}

}