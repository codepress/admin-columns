class Modals {

	constructor() {
		this.modals = [];
		this.number = 1;
	}

	register( modal, key = '' ) {
		if ( !key ) {
			key = 'm' + this.number;
		}

		this.modals[ key ] = modal;
		this.number++;

		return modal;
	}

	get( key ) {
		if ( this.modals[ key ] ) {
			return this.modals[ key ];
		}

		return false;
	}

	// Bind self to global AdminColumns if exist
	static init() {
		if ( typeof AdminColumns.Modals === 'undefined' ) {
			AdminColumns.Modals = new this();
		}

		return AdminColumns.Modals;
	}

}

module.exports = Modals;