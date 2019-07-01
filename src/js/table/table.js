import Actions from "./actions";
import Cells from "./cells";
import Columns from "./columns";
import Cell from "./cell";
import Helper from "./helper";
import Selection from "./row-selection";

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
		this.Actions = new Actions( 'ac-table-actions' );
		this.Selection = new Selection( this );
		this._ids = [];

		this.init();
	}

	init() {
		let self = this;

		this._initTable();
		this.addCellClasses();

		document.dispatchEvent( new CustomEvent( 'AC_Table_Ready', { detail : { self } } ) );
	}

	updateRow( row ) {
		let id = this._getIDFromRow( row );

		row.dataset.id = id;
		this._setCellsForRow( row, id );
	}

	addCellClasses() {
		let self = this;
		this.Columns.getColumnNames().forEach( ( name ) => {
			let type = self.Columns.get( name ).type;
			let cells = self.Cells.getByName( name );

			cells.forEach( ( cell ) => {
				cell.el.classList.add( type );
			} );
		} );
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

			self._ids.push( id );

			this.updateRow( row );
		}

	}

	_setCellsForRow( row ) {
		let id = this._getIDFromRow( row );

		this.Columns.getColumnNames().forEach( ( name ) => {
			let selector = name.replace( /\./g, '\\.' );
			let td = row.querySelector( ".column-" + selector );

			if ( td ) {
				let cell = new Cell( id, name, td );
				this.Cells.add( id, cell );
				this._addColumnCellMethods( cell );
			}
		} );
	}

	_addColumnCellMethods( column ) {
		column.el.getCell = function() {
			return column;
		}
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
		let id_parts = id.split( /[_,\-]+/ );
		let item_id = id_parts[ id_parts.length - 1 ];

		if ( row.classList.contains( 'no-items' ) ) {
			return 0;
		}

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

			if ( link ) {
				let href = link.getAttribute( 'href' );

				if ( href ) {
					item_id = this.Helper.getParamFromUrl( 'id', href );
				}
			}

		}

		row.dataset.id = item_id;

		document.dispatchEvent( new CustomEvent( 'AC_Table_Row_Id', { detail : { row : row } } ) );

		return row.dataset.id;
	}

	getRowCellByName( row, column_name ) {
		return row.querySelector( `.column-${column_name}` );
	}

	static getTable( jQuery = false ) {
		if ( jQuery ) {
			return jQuery( this.el );
		}

		return this.el;
	}

}