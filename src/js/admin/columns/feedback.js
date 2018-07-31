class Feedback {

	constructor( $el ) {
		this.$el = jQuery( $el );
		this.init();
	}

	init() {
		let $box = this.$el;

		$box.find( '#feedback-choice a.no' ).click( function( e ) {
			e.preventDefault();

			$box.find( '#feedback-choice' ).slideUp();
			$box.find( '#feedback-support' ).slideDown();
		} );

		$box.find( '#feedback-choice a.yes' ).click( function( e ) {
			e.preventDefault();

			$box.find( '#feedback-choice' ).slideUp();
			$box.find( '#feedback-rate' ).slideDown();
		} );
	};

}

module.exports = Feedback;