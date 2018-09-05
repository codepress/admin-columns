export default class Column {

	constructor( id, name, el ) {
		this._object_id = id;
		this._column_name = name;

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

	setValue( value ) {
		let el = this.getElement();

		el.innerHTML = value;

		return this;
	}

}