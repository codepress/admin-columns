import Actions from "./actions";
import Columns from "./columns";
import Column from "./column";

export default class Table {

	/**
	 *
	 * @param {Element} el
	 */
	constructor( el ) {
		this.el = el;
		this.Columns = new Columns( el );
		this.Buttons = new Actions( '#ac-table-actions' );

		this.init();
	}

	init() {
		let self = this;
		this._initTable();

		document.dispatchEvent( new CustomEvent( 'AC_Table_Ready', { detail : { self } } ) );
	}

	/**
	 * Initiate the table so we can easily query it
	 * Also populate the Columns Model
	 *
	 * @private
	 */
	_initTable() {
		let self = this;
		let el = this.el.getElementsByTagName( 'tbody' );
		let rows = el[ 0 ].getElementsByTagName( 'tr' );

		for ( let i = 0; i < rows.length; i++ ) {
			let row = rows[ i ];
			let id = Table._getIDFromRow( row );

			row.dataset.id = id;

			self.Columns.getColumnTypes().forEach( function( name ) {
				let td = row.querySelector( `.column-${name}` );

				if ( td ) {
					self.Columns.addColumn( id, new Column( id, name, td ) );
				}
			} );
		}

	}

	/**
	 * Get the Post ID from a table row based on it's attributes or columns
	 *
	 * @param {Element} row
	 * @returns {int}
	 * @private
	 */
	static _getIDFromRow( row ) {
		let id = row.id;
		let id_parts = id.split( '-' );
		let item_id = id_parts[ id_parts.length - 1 ];

		if ( !item_id ) {
			let input = row.querySelector( '.check-column input[type=checkbox]' );

			if ( input ) {
				id = input.id;
				id_parts = id.split( '_' );
				item_id = id_parts[ id_parts.length - 1 ];
			}

		}

		return item_id;
	}

	static getTable( jQuery = false ) {
		if ( jQuery ) {
			return jQuery( this.el );
		}

		return this.el;
	}

	getRow( id ) {
		return this.el.querySelector( `tr#${id}` );
	}

}