class Initiator {

	constructor() {
		this.events = {};
		this.settings = {};
		this.incremental_name = 0;
	}

	registerSetting( k, setting ) {
		let key = 's_' + k;

		if ( this.settings[ key ] ) {
			console.error( 'Setting key already exists: ' + k );
		}

		this.settings[ key ] = setting;

		return this;
	}

	registerEvent( k, event ) {
		let key = 'e_' + k;
		if ( this.settings[ key ] ) {
			console.error( 'Event key already exists: ' + key );
		}

		this.events[ key ] = event;

		return this;
	}

	getNewIncementalName() {
		let oldName = this.incremental_name;
		this.incremental_name++;

		return oldName;
	}
}

module.exports = Initiator;