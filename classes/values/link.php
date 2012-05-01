<?php

/**
 * CPAC_Link_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Link_Values extends CPAC_Values
{	
	/**
	 * Constructor
	 *
	 * @since     1.4.4
	 */
	function __construct()
	{
		parent::__construct();
		
		add_action( 'manage_link_custom_column', array( $this, 'manage_link_column_value'), 10, 2 );
	}
	
	/**
	 * Manage custom column for Links
	 *
	 * @since     1.3.1
	 */
	public function manage_link_column_value( $column_name, $link_id )
	{
		$type = $column_name;
		
		// links object... called bookmark
		$bookmark = get_bookmark($link_id);

		// Hook 
		do_action('cpac-manage-link-column', $type, $column_name, $link_id);
		
		$result = '';
		switch ($type) :			
			
			// link id
			case "column-link_id" :
				$result = $link_id;
				break;
			
			// description
			case "column-description" :
				$result = $bookmark->link_description;
				break;
			
			// target
			case "column-target" :
				$result = $bookmark->link_target;
				break;
			
			// notes
			case "column-notes" :
				$result = $this->get_shortened_string($bookmark->link_notes, $this->excerpt_length);
				break;
			
			// rss
			case "column-rss" :
				$result 	= $this->get_shorten_url($bookmark->link_rss);
				break;
				
			// image
			case "column-image" :
				$result = $this->get_thumbnail($bookmark->link_image);
				break;
				
			// name length
			case "column-length" :				
				$result = strlen($bookmark->link_name);
				break;
				
			// owner
			case "column-owner" :
				$result = $bookmark->link_owner;
				
				// add user link
				$userdata = get_userdata( $bookmark->link_owner );				
				if (!empty($userdata->data)) {
					$result = $userdata->data->user_nicename;
					//$result = "<a href='user-edit.php?user_id={$bookmark->link_owner}'>{$result}</a>";
				}
				break;
			
			default :
				$result = '';
			
		endswitch;
		
		echo $result;
	}
}

?>