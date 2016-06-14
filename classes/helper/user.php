<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_User {

	/**
	 * Format list of options for users selection
	 *
	 * Results are formatted as an array of roles, the key being the role name, the value
	 * being an array with two keys: label (the role label) and options, an array of options (users)
	 * for this role, with the user IDs as keys and the user display names as values
	 *
	 * @since 1.0
	 * @uses WP_User_Query
	 *
	 * @param array $args User query args
	 * @param string $format
	 *
	 * @return array Grouped users by role
	 */
	public function get_users_for_selection( $args = array(), $format = '' ) {

		$options = array();

		if ( ! empty( $args['search'] ) ) {
			$args['search'] = '*' . $args['search'] . '*';
		}

		$args = wp_parse_args( $args, array(
			'orderby'        => 'display_name',
			'search_columns' => array( 'ID', 'user_login', 'user_nicename', 'user_email', 'user_url' ),
			'number'         => 50,
			'paged'          => 1,
			'fields'         => 'ID'
		) );

		if ( ! is_numeric( $args['paged'] ) ) {
			$args['paged'] = 1;
		}

		$users_query = new WP_User_Query( $args );

		if ( $user_ids = $users_query->get_results() ) {
			$roles = get_editable_roles();

			foreach ( $user_ids as $user_id ) {
				$user = get_userdata( $user_id );

				// Display format
				if ( 'first_last_name' === $format ) {
					$format = array( 'first_name', 'last_name' );
				}

				$name_parts = array();
				if ( $format = (array) $format ) {
					foreach ( $format as $field ) {
						if ( ! empty( $user->{$field} ) ) {
							$name_parts[] = $user->{$field};
						}
					}
				}

				if ( empty( $name_parts ) ) {
					$name_parts = array( $user->display_name );
				}

				$name = implode( ' ', $name_parts );

				// Add login name
				$name .= ' (' . $user->user_login . ')';

				// Group by role
				$role = array_shift( $user->roles );

				if ( ! isset( $grouped[ $role ] ) ) {
					$label = translate_user_role( $roles[ $role ]['name'] );
					$options[ $role ] = array(
						'label'   => $label ? $label : '',
						'options' => array()
					);
				}

				$options[ $role ]['options'][ $user->ID ] = $name;
			}
		}

		return $options;
	}

	/**
	 * @param string $field
	 * @param int $user_id
	 *
	 * @return bool|string
	 */
	public function get_user_field( $field, $user_id ) {
		$user = get_user_by( 'id', $user_id );

		return isset( $user->{$field} ) ? $user->{$field} : false;
	}
}