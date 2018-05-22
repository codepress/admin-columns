<?php

namespace AC\Admin\Help;

use AC\Admin\Help;

class Basics extends Help {

	public function get_title() {
		return __( "Basics", 'codepress-admin-columns' );
	}

	public function get_content() {
		ob_start();
		?>

        <h5><?php _e( "Change order", 'codepress-admin-columns' ); ?></h5>
        <p>
            <?php _e( "By dragging the columns you can change the order which they will appear in.", 'codepress-admin-columns' ); ?>
        </p>

        <h5><?php _e( "Change label", 'codepress-admin-columns' ); ?></h5>
        <p>
            <?php _e( "By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.", 'codepress-admin-columns' ); ?>
        </p>

        <h5><?php _e( "Change column width", 'codepress-admin-columns' ); ?></h5>
        <p>
            <?php _e( "By clicking on the triangle you will see the column options. By using the draggable slider you can set the width of the columns in percentages.", 'codepress-admin-columns' ); ?>
        </p>

		<?php

		return ob_get_clean();
	}

}