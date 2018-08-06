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
	}

	get( key ) {
		if ( this.modals[ key ] ) {
			return this.modals[ key ];
		}

		return false;
	}
}

module.exports = Modals;