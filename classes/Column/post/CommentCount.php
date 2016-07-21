<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying the number of comments for an item, displaying either the total
 * amount of comments, or the amount per status (e.g. "Approved", "Pending").
 *
 * @since 2.0
 */
class AC_Column_Post_CommentCount extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-comment_count';
		$this->properties['label'] = __( 'Comment count', 'codepress-admin-columns' );
	}

	function get_comment_stati() {
		return array(
			'total_comments' => __( 'Total', 'codepress-admin-columns' ),
			'approved'       => __( 'Approved', 'codepress-admin-columns' ),
			'moderated'      => __( 'Pending', 'codepress-admin-columns' ),
			'spam'           => __( 'Spam', 'codepress-admin-columns' ),
			'trash'          => __( 'Trash', 'codepress-admin-columns' ),
		);
	}

	function get_value( $post_id ) {
		$value = '';

		$status = $this->get_option( 'comment_status' );
		$count = $this->get_raw_value( $post_id );

		if ( $count !== '' ) {
			$names = $this->get_comment_stati();

			$url = esc_url( add_query_arg( array( 'p' => $post_id, 'comment_status' => $status ), admin_url( 'edit-comments.php' ) ) );
			$value = "<a href='{$url}' class='cp-{$status}' title='" . $names[ $status ] . "'>{$count}</a>";
		}

		return $value;
	}

	function get_raw_value( $post_id ) {
		$value = '';

		$status = $this->get_option( 'comment_status' );
		$count = wp_count_comments( $post_id );

		if ( isset( $count->{$status} ) ) {
			$value = $count->{$status};
		}

		return $value;
	}

	function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	function display_settings() {
		$this->field_settings->field( array(
			'type'        => 'select',
			'name'        => 'comment_status',
			'label'       => __( 'Comment status', 'codepress-admin-columns' ),
			'description' => __( 'Select which comment status you like to display.', 'codepress-admin-columns' ),
			'options'     => $this->get_comment_stati()
		) );
	}

}