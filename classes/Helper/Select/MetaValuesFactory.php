<?php

namespace AC\Helper\Select;

use AC\MetaType;

class MetaValuesFactory {

	/**
	 * @param string $meta_type
	 * @param string $meta_key
	 * @param string $post_type
	 *
	 * @return array
	 */
	public static function create( $meta_type, $meta_key, $post_type = null ) {
		global $wpdb;

		switch ( $meta_type ) {
			case MetaType::POST :
				$sql = $wpdb->prepare( "
					SELECT DISTINCT pm.meta_value
					FROM {$wpdb->postmeta} AS pm
					LEFT JOIN {$wpdb->posts} AS ps ON ps.ID = pm.post_id
					WHERE pm.meta_key = %s
					    AND pm.meta_value != ''
					    AND ps.post_status != 'trash'
				", $meta_key );

				if ( $post_type ) {
					$sql .= $wpdb->prepare( " AND ps.post_type = %s", $post_type );
				}

				return $wpdb->get_col( $sql );

			case MetaType::USER :
				return $wpdb->get_col( $wpdb->prepare( "
					SELECT DISTINCT um.meta_value
					FROM {$wpdb->usermeta} AS um
					LEFT JOIN {$wpdb->users} AS us ON us.ID = um.user_id
					WHERE um.meta_key = %s
					    AND um.meta_value != ''
					;
				", $meta_key ) );

			case MetaType::COMMENT :
				return $wpdb->get_col( $wpdb->prepare( "
					SELECT DISTINCT cm.meta_value
					FROM {$wpdb->commentmeta} AS cm
					LEFT JOIN {$wpdb->comments} AS cs ON cs.comment_ID = cm.comment_id
					WHERE cm.meta_key = %s
					    AND cm.meta_value != ''
					;
				", $meta_key ) );

			case MetaType::TERM :
				return $wpdb->get_col( $wpdb->prepare( "
					SELECT DISTINCT tm.meta_value
					FROM {$wpdb->termmeta} AS tm
					LEFT JOIN {$wpdb->terms} AS ts ON ts.term_id = tm.term_id
					WHERE tm.meta_key = %s
					    AND tm.meta_value != ''
					;
				", $meta_key ) );

			default :
				return [];

		}
	}

}