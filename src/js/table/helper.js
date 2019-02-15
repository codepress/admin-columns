export default class Helper {

	/**
	 * Get query param from url
	 *
	 * @param param
	 * @param url
	 * @returns {*}
	 */
	static getParamFromUrl( param, url ) {
		if ( !url ) {
			return null;
		}
		param = param.replace( /[\[\]]/g, "\\$&" );

		let regex = new RegExp( "[?&]" + param + "(=([^&#]*)|&|#|$)" ), results = regex.exec( url );

		if ( !results ) {
			return null;
		}

		if ( !results[ 2 ] ) {
			return '';
		}

		return decodeURIComponent( results[ 2 ].replace( /\+/g, " " ) );
	};

}