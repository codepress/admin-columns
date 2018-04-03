<?php

class AC_Admin_Help_CustomField extends AC_Admin_Help {

	public function get_title() {
		return __( "Custom Fields", 'codepress-admin-columns' );
	}

	public function get_content() {
		ob_start();
		?>

        <p>
            <?php _e( "The custom field column uses the custom fields from posts and users. There are 14 types which you can set.", 'codepress-admin-columns' ); ?>
        </p>
        <ul>
            <li>
                <strong><?php _e( "Default", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Checkmark", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: should be a 1 (one) or 0 (zero).", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Color", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: hex value color, such as #808080.", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Counter", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: Can be either a string or array. This will display a count of the number of times the meta key is used by the item.", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Date", 'codepress-admin-columns' ); ?></strong><br/>
                <?php printf( __( "Value: Can be unix time stamp or a date format as described in the <a href='%s'>Codex</a>. You can change the outputted date format at the <a href='%s'>general settings</a> page.", 'codepress-admin-columns' ), 'http://codex.wordpress.org/Formatting_Date_and_Time', admin_url( 'options-general.php' ) ); ?>
            </li>
            <li>
                <strong><?php _e( "Excerpt", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: This will show the first 20 words of the Post content.", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Image", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: should contain an image URL or Attachment IDs ( seperated by a ',' comma ).", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Media Library", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: should contain Attachment IDs ( seperated by a ',' comma ).", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Multiple Values", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: should be an array. This will flatten any ( multi dimensional ) array.", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Numeric", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Post Titles", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: can be one or more Post ID's (seperated by ',').", 'codepress-admin-columns' ); ?>
            </li>
            <li>
                <strong><?php _e( "Usernames", 'codepress-admin-columns' ); ?></strong><br/>
                <?php _e( "Value: can be one or more User ID's (seperated by ',').", 'codepress-admin-columns' ); ?>
            </li>
        </ul>

		<?php

		return ob_get_clean();
	}

}