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
		if ( typeof AC_Modals === 'undefined' ) {
			global.AC_Modals = new this();
		}
	}
}

module.exports = Modals;