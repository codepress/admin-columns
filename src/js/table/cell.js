export default class Cell {

	constructor( id, name, el ) {
		this._object_id = id;
		this._column_name = name;
		this._original_value = el.innerHTML;

		this.el = el;
	}

	getObjectID() {
		return this._object_id;
	}

	getName() {
		return this._column_name;
	}

	getElement() {
		return this.el;
	}

	getRow() {
		return this.el.parentElement;
	}

	getSettings() {
		return AdminColumns.Table.Columns.get( this.getName() );
	}

	hasChanged( content ) {
		return this._original_value !== content;
	}

	setValue( value ) {
		let el = this.getElement();

		this._original_value = value;
		el.innerHTML = value;

		return this;
	}

}