<?php
/**
 * CPAC_Column_Post_Estimated_Reading_Time
 *
 * @since 2.3.3
 */
class CPAC_Column_Post_Estimated_Reading_Time extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.3.3
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-estimated_reading_time';
		$this->properties['label']	 = __( 'Estimated Reading Time', 'cpac' );

		// Options
		$this->options['words_per_minute'] = 200;
	}

	/**
	 * Estimate read time in readable format
	 *
	 * @see CPAC_Column::get_value()
	 * @since 2.3.3
	 */
	public function get_value( $post_id ) {

		return $this->convert_seconds_to_readable_time( $this->get_raw_value( $post_id ) );
	}

	/**
	 * Estimate read time in seconds
	 *
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.3
	 */
	public function get_raw_value( $post_id ) {

		return $seconds = $this->get_estimated_reading_time_in_seconds( get_post_field( 'post_content', $post_id ) );
	}

	/**
	 * @since 2.3.3
	 */
	public function convert_seconds_to_readable_time( $seconds ) {

		$minutes = floor( $seconds / 60 );
		$seconds = floor( $seconds % 60 );

		$time = $minutes;
		if ( $seconds < 10 ) {
			$seconds = '0' . $seconds;
		}
		if ( '00' != $seconds ) {
			$time .= ':' . $seconds;
		}
		if ( $minutes < 1 ) {
			$time = $seconds . ' ' . _n( 'second', 'seconds', $seconds, 'cpac' );
		}
		else {
			$time .= ' ' . _n( 'minute', 'minutes', $minutes, 'cpac' );
		}

		return $time;
	}

	/**
	 * @since 2.3.3
	 */
	public function get_estimated_reading_time_in_seconds( $content ) {

		$word_count = $this->str_count_words( $this->strip_trim( $content ) );
		if ( ! $word_count ) {
			return 0;
		}
		return (int) floor( ( $word_count / $this->options->words_per_minute ) * 60 );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.3.3
	 */
	public function apply_conditional() {

		if ( post_type_supports( $this->storage_model->key, 'editor' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @since 2.3.3
	 */
	public function display_settings() {

		$field_key		= 'words_per_minute';
		$label			= __( 'Words per minute', 'cpac' );
		$description	= __( 'Estimated reading time in words per minute', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>">
			<?php $this->label_view( $label, $description, $field_key ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>" value="<?php echo $this->options->words_per_minute; ?>"/>
			</td>
		</tr>
	<?php
	}
}