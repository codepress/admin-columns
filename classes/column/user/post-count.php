<?php

/**
 * CPAC_Column_User_Post_Count
 *
 * @since 2.0.0
 */
class CPAC_Column_User_Post_Count extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 = 'column-user-postcount';
		$this->properties['label']	 = __( 'Post Count', CPAC_TEXTDOMAIN );
		
		// define additional options
		$this->options['post_type'] = '';
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $user_id ) {
		
		global $wpdb;
		
		$value = '0';
		
		$sql = "
			SELECT COUNT(ID) 
			FROM {$wpdb->posts} 
			WHERE post_status = 'publish' 
			AND post_author = %d 
			AND post_type = %s
		";
		
		$count = $wpdb->get_var( $wpdb->prepare( $sql, $user_id, $this->options->post_type ) );

		if ( $count > 0 ) 
			$value = "<a href='edit.php?post_type={$this->options->post_type}&author={$user_id}'>{$count}</a>";
		
		return $value;
	}
	
	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {
		
		// get posttypes and name
		$post_types = array();		
		foreach ( CPAC_Utility::get_post_types() as $type ) {
			$obj = get_post_type_object( $type );			
			$post_types[ $type ] = $obj->labels->singular_name;
		}		
		
		?>
		
		<tr class="<?php $this->properties->type; ?>">			
			<?php $this->label_view( __( 'Post Type', CPAC_TEXTDOMAIN ), '', 'post_type' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'post_type' ); ?>" id="<?php $this->attr_id( 'post_type' ); ?>">				
				<?php foreach ( $post_types as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $key, $this->options->post_type ) ?>><?php echo $label; ?></option>
				<?php endforeach; ?>				
				</select>
			</td>
		</tr>		
		<?php 
	}
}