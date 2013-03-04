<?php
/**
 * CPAC_Column_Post_Author_Name
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Author_Name extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-author_name';
		$this->properties['label']	 	= __( 'Display Author As', 'cpac' );

		// define additional options
		$this->options['display_author_as'] = '';

		parent::__construct( $storage_model );
	}

	/**
	 * Get author field by nametype
	 *
	 * Used by posts and sortable
	 *
	 * @since 2.0.0
	 *
	 * @return array Authortypes
	 */
	private function get_nametypes() {

		$nametypes = array(
			'display_name'		=> __( 'Display Name', 'cpac' ),
			'first_name'		=> __( 'First Name', 'cpac' ),
			'last_name'			=> __( 'Last Name', 'cpac' ),
			'nickname'			=> __( 'Nickname', 'cpac' ),
			'user_login'		=> __( 'User Login', 'cpac' ),
			'user_email'		=> __( 'User Email', 'cpac' ),
			'ID'				=> __( 'User ID', 'cpac' ),
			'first_last_name'	=> __( 'First and Last Name', 'cpac' ),
		);

		return $nametypes;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$value = '';

		$nametypes = $this->get_nametypes();
		if ( isset( $nametypes[ $this->options->display_author_as ] ) ) {

			if( $author = get_post_field( 'post_author', $post_id ) ) {

				$display_as = $this->options->display_author_as;
				$userdata 	= get_userdata( $author );

				// first check variables in userdata
				if ( ! empty( $userdata->{$display_as} ) ) {
					$value = $userdata->{$display_as};
				}

				elseif ( 'first_last_name' == $display_as ) {
					$first 	= !empty($userdata->first_name) ? $userdata->first_name : '';
					$last 	= !empty($userdata->last_name) ? " {$userdata->last_name}" : '';
					$value 	= $first.$last;
				}
			}
		}

		return $value;
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		?>

		<tr class="column-author-name">
			<?php $this->label_view( $this->properties->label, __( 'This is the format of the author name.', 'cpac' ), 'display_author_as' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'display_author_as' ); ?>" id="<?php $this->attr_id( 'display_author_as' ); ?>">
				<?php foreach ( $this->get_nametypes() as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $key, $this->options->display_author_as ) ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php
	}
}