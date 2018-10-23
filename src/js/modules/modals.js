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

	// Bind self to global AC if exist
	static init() {
		let modals = new this();

		if ( typeof AC === 'undefined' ) {
			let newAC = {};
			newAC.Modals = modals;

			global.AC = newAC;
		} else {
			AC.Modals = modals;
		}
	}
}

module.exports = Modals;