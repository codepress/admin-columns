<?php

class AC_Settings_View_Field extends AC_Settings_ViewAbstract {

	public function template() {

		?>

		<table class="widefat <?php echo esc_attr( $this->class ); ?>" data-events="<?php echo $this->events; ?>">
			<tr>
				<td class="label">
					<?php echo $this->label; ?>
				</td>
				<td class="input">
					<?php echo $this->group; ?>
					<?php echo $this->subfields; ?>
				</td>
			</tr>
		</table>

		<?php

	}

}