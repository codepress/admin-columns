<?php

use AC\Admin\Admin;
use AC\Form\Element\Select;

if ( ! defined('ABSPATH')) {
    exit;
}

?>

<div class="menu <?php
echo $this->class; ?>">
	<form>
		<input type="hidden" name="page" value="<?php
        echo esc_attr(Admin::NAME); ?>">

        <?php
        $select = new Select('list_screen', $this->items);

        $select->set_value($this->current)
               ->set_attribute('title', __('Select type', 'codepress-admin-columns'))
               ->set_attribute('id', 'ac_list_screen');

        echo $select->render();

        ?>

		<span class="spinner"></span>

        <?php
        if ($this->screen_link) : ?>
			<a href="<?php
            echo esc_url($this->screen_link); ?>" class="page-title-action view-link"><?php
                esc_html_e('View', 'codepress-admin-columns'); ?></a>
        <?php
        endif; ?>
	</form>
</div>