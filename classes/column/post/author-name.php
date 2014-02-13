<?php
/**
 * CPAC_Column_Post_Author_Name
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Author_Name extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 			= 'column-author_name';
		$this->properties['label']	 			= __( 'Display Author As', 'cpac' );
		$this->properties['is_cloneable']		= true;
		$this->properties['object_property']	= 'post_author';

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
	 * Get display name.
	 *
	 * Can also be used by addons.
	 *
	 * @since 2.0.0
	 */
	public function get_display_name( $user_id ) {

		if ( ! $userdata = get_userdata( $user_id ) )
			return false;

		$name = '';

		$display_as = $this->options->display_author_as;

		if ( 'first_last_name' == $display_as ) {
			$first 	= ! empty( $userdata->first_name ) ? $userdata->first_name : '';
			$last 	= ! empty( $userdata->last_name ) ? " {$userdata->last_name}" : '';
			$name 	= $first.$last;
		}

		elseif ( ! empty( $userdata->{$display_as} ) ) {
			$name = $userdata->{$display_as};
		}

		// default to display_name
		if ( ! $name ) {
			$name = $userdata->display_name;
		}

		return $name;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$value = '';

		$nametypes = $this->get_nametypes();
		if ( isset( $nametypes[ $this->options->display_author_as ] ) ) {
			if( $author = $this->get_raw_value( $post_id ) ) {
				$value = $this->get_display_name( $author );
			}
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_post_field( 'post_author', $post_id );
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