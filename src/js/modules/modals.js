class Modals {

	constructor() {
		this.modals = [];
	}

	register( modal, key = '' ) {
		if ( !key ) {
			key = Math.random();
		}

		this.modals[ key ] = modal;
	}

	get( key ) {
		if ( this.modals[ key ] ) {
			return this.modals[ key ];
		}

		return false;
	}
}

module.exports = Modals;