import Actions from "./actions";
import Cells from "./cells";
import Columns from "./columns";
import Column from "./column";
import Helper from "./helper";

export default class Table {

	/**
	 *
	 * @param {Element} el
	 */
	constructor( el ) {
		this.el = el;
		this.Helper = Helper;
		this.Columns = new Columns( el );
		this.Cells = new Cells();
		this.Buttons = new Actions( '#ac-table-actions' );

		this.init();
	}

	init() {
		let self = this;

		this._initTable();
		this._addCellMethods();

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
			let id = this._getIDFromRow( row );

			row.dataset.id = id;

			self.Columns.getColumnNames().forEach( function( name ) {
				let td = row.querySelector( `.column-${name}` );

				if ( td ) {
					self.Cells.add( id, new Column( id, name, td ) );
				}
			} );
		}

	}

	_addCellMethods() {
		// Attach method to retrieve the column reference
		this.Cells.getAll().forEach( function( column ) {
			column.el.getCell = function() {
				return column;
			}
		} );
	}

	/**
	 * Get the Post ID from a table row based on it's attributes or columns
	 *
	 * @param {Element} row
	 * @returns {int}
	 * @private
	 */
	_getIDFromRow( row ) {
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

		// Try to get the ID from the edit URL (MS Sites)
		if ( !item_id ) {
			let link = row.parentElement.querySelector( '.edit a' );
			let href = link.getAttribute( 'href' );

			if ( href ) {
				item_id = this.Helper.getParamFromUrl( 'id', href );
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