import Actions from "./actions";

class Table {

	constructor( el ) {
		this.$table = jQuery( el );
		this.Buttons = new Actions( '#ac-table-actions' );
	}

}

module.exports = Table;