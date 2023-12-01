<?php

namespace AC\Column\Post;

use AC;
use AC\Column;
use AC\Settings;

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 * @since 2.0
 */
class LastModifiedAuthor extends Column\Meta {

	public function __construct() {
		$this->set_type( 'column-last_modified_author' )
		     ->set_label( __( 'Last Modified Author', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$raw_value = $this->get_raw_value( $id );

		if ( ! $raw_value ) {
			return $this->get_empty_char();
		}

        // TODO Stefan make sure this follows new (int) $id for get_formatted_value()
		return $this->get_formatted_value( $raw_value, $raw_value );
	}

	public function get_meta_key() {
		return '_edit_last';
	}

	/**
	 * @return string
	 */
	protected function get_user_setting_display() {
		/**
		 * @var AC\Settings\Column\User $setting
		 */
		$setting = $this->get_setting( 'user' );

		return $setting->get_display_author_as();
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\User( $this ) );
	}

}