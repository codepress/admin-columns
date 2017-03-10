<?php

class AC_Admin_Help_Introduction extends AC_Admin_Help {

	public function get_title() {
		return __( "Overview", 'codepress-admin-columns' );
	}

	public function get_content() {
		ob_start();
		?>

        <p>
			<?php _e( "This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.", 'codepress-admin-columns' ); ?>
        </p>

		<?php

		return ob_get_clean();
	}

}