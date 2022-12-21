<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;
use DateTime;
use LogicException;
use WP_Comment;

class CommentSummary implements ValueFormatter {

	public function format_value( $entity ): string {
		if ( ! $entity instanceof WP_Comment ) {
			throw new LogicException( 'Invalid comment.' );
		}

		$date = new DateTime( $entity->comment_date );

		$value = array_filter( [
			$entity->comment_author_email,
			$date->format( 'M j, Y H:i' ),
		] );

		return $entity->comment_ID . ' - ' . implode( ' / ', $value );
	}

}