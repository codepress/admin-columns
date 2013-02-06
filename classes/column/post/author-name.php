<?php
/**
 * CPAC_Column_Post_Author_Name
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Author_Name extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 	= 'column-author-name';
		$this->properties['label']	 	= __( 'Display Author As', CPAC_TEXTDOMAIN );
		
		// define additional options
		$this->options['display_author_as'] = '';
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * Get author field by nametype
	 *
	 * Used by posts and sortable
	 *
	 * @since 1.4.6.1
	 *
	 * @param string $nametype
	 * @param int $user_id
	 * @return string Author
	 */
	private function get_nametypes() {
		
		$nametypes = array(
			'display_name'		=> __( 'Display Name', CPAC_TEXTDOMAIN ),
			'first_name'		=> __( 'First Name', CPAC_TEXTDOMAIN ),
			'last_name'			=> __( 'Last Name', CPAC_TEXTDOMAIN ),
			'nickname'			=> __( 'Nickname', CPAC_TEXTDOMAIN ),
			'user_login'		=> __( 'User Login', CPAC_TEXTDOMAIN ),
			'user_email'		=> __( 'User Email', CPAC_TEXTDOMAIN ),
			'ID'				=> __( 'User ID', CPAC_TEXTDOMAIN ),
			'first_last_name'	=> __( 'First and Last Name', CPAC_TEXTDOMAIN ),
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
			<?php $this->label_view( $this->properties->label, '', 'display_author_as' ); ?>
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