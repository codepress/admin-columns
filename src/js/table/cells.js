export default class Cells {

	constructor() {
		this._cells = new Map();
	}

	add( id, column ) {
		if ( !this._cells.has( id ) ) {
			this._cells.set( id, new Map() );
		}

		this._cells.get( id ).set( column.getName(), column );
	}

	getByID( id ) {
		let result = [];
		let key = id.toString();
		if ( !this._cells.has( key ) ) {
			return result;
		}

		this._cells.get( id.toString() ).forEach( function( column ) {
			result.push( column );
		} );

		return result;
	}

	getAll() {
		let results = [];

		this._cells.forEach( function( columns ) {

			columns.forEach( function( column ) {
				results.push( column );
			} )

		} );

		return results;
	}

	getByType( type ) {
		let results = [];

		this._cells.forEach( function( columns ) {

			columns.forEach( function( column, name ) {
				if ( name === type ) {
					results.push( column );
				}
			} );

		} );

		return results;
	}

	get( id, type ) {
		let row = this._cells.get( id.toString() );

		if ( !row ) {
			return false;
		}

		return row.get( type );
	}

}