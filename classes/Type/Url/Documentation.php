<?php

namespace AC\Type\Url;

use AC\Type;

class Documentation implements Type\Url {

	use Path;

	const URL = 'https://docs.admincolumns.com';

	const ARTICLE_ACTIONS_FILTERS = '/article/15-hooks-and-filters';
	const ARTICLE_CUSTOM_FIELD = '/article/59-custom-field-columns';
	const ARTICLE_CUSTOM_FIELD_EDITING = '/article/68-enable-editing-for-custom-field-columns';
	const ARTICLE_INLINE_EDITING = '/article/27-how-to-use-inline-editing';
	const ARTICLE_SORTING = '/article/34-how-to-enable-sorting';
	const ARTICLE_LOCAL_STORAGE = '/article/58-how-to-setup-local-storage';
	const ARTICLE_SMART_FILTERING = '/article/61-how-to-use-smart-filtering';
	const ARTICLE_BULK_EDITING = '/article/67-how-to-use-bulk-editing';
	const ARTICLE_ENABLE_EDITING = '/article/68-enable-editing-for-custom-field-columns';
	const ARTICLE_EXPORT = '/article/69-how-to-use-export';
	const ARTICLE_QUICK_ADD = '/article/71-how-to-use-quick-add';
	const ARTICLE_COLUMN_SETS = '/article/72-how-to-create-column-sets';
	const ARTICLE_SAVED_FILTERS = '/article/73-how-to-use-saved-filters';
	const ARTICLE_SHOW_ALL_SORTING_RESULTS = '/article/86-show-all-results-when-sorting';
	const ARTICLE_UPGRADE_V3_TO_V4 = '/article/91-how-to-upgrade-from-v3-to-v4';

	/**
	 * @param string $path
	 */
	public function __construct( $path = null ) {
		if ( $path ) {
			$this->set_path( $path );
		}
	}

	public function get_url() {
		return self::URL . $this->get_path();
	}

	public static function create_with_path( $path ) {
		return new self( $path );
	}

	public function __toString() {
		return $this->get_url();
	}

}