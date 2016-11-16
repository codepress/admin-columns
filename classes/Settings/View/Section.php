<?php

class AC_Settings_View_Section extends AC_Settings_ViewAbstract {

	public function template() {

		?>

		<table class="widefat <?php echo esc_attr( $this->class ); ?>" data-events="<?php echo $this->events; ?>">
			<tr>
				<td class="label">
					<?php echo $this->label; ?>
				</td>
				<td class="input">
					<?php echo $this->field; ?>
					<?php echo $this->sections; ?>
				</td>
			</tr>
		</table>

		<?php

	}

}