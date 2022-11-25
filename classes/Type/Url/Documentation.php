<?php

namespace AC\Type\Url;

use AC\Type;

class Documentation implements Type\Url {

	use Path, Fragment;

	private const URL = 'https://docs.admincolumns.com';

	public const ARTICLE_ACF_UPGRADE_V2_TO_V3 = '/article/103-how-to-upgrade-the-acf-integration-from-v2-to-v3';
	public const ARTICLE_ACTIONS_FILTERS = '/article/15-hooks-and-filters';
	public const ARTICLE_BULK_EDITING = '/article/67-how-to-use-bulk-editing';
	public const ARTICLE_COLUMN_SETS = '/article/72-how-to-create-column-sets';
	public const ARTICLE_CONDITIONAL_FORMATTING = '/article/108-how-to-use-conditional-formatting';
	public const ARTICLE_CUSTOM_FIELD = '/article/59-custom-field-columns';
	public const ARTICLE_CUSTOM_FIELD_EDITING = '/article/68-enable-editing-for-custom-field-columns';
	public const ARTICLE_ENABLE_EDITING = '/article/68-enable-editing-for-custom-field-columns';
	public const ARTICLE_EXPORT = '/article/69-how-to-use-export';
	public const ARTICLE_INLINE_EDITING = '/article/27-how-to-use-inline-editing';
	public const ARTICLE_LOCAL_STORAGE = '/article/58-how-to-setup-local-storage';
	public const ARTICLE_QUICK_ADD = '/article/71-how-to-use-quick-add';
	public const ARTICLE_SAVED_FILTERS = '/article/73-how-to-use-saved-filters';
	public const ARTICLE_SHOW_ALL_SORTING_RESULTS = '/article/86-show-all-results-when-sorting';
	public const ARTICLE_SMART_FILTERING = '/article/61-how-to-use-smart-filtering';
	public const ARTICLE_SORTING = '/article/34-how-to-enable-sorting';
	public const ARTICLE_SUBSCRIPTION_QUESTIONS = '/article/96-subscription-or-license-questions';
	public const ARTICLE_UPGRADE_V3_TO_V4 = '/article/91-how-to-upgrade-from-v3-to-v4';
	public const ARTICLE_RELEASE_6 = '/article/109-admin-columns-pro-6-0-changes';

	/**
	 * @param string $path
	 */
	public function __construct( $path = null, $fragment = null ) {
		if ( $path ) {
			$this->set_path( $path );
		}

		if ( $fragment ) {
			$this->set_fragment( $fragment );
		}
	}

	public function get_url() {
		$url = self::URL . $this->get_path();

		if ( $this->has_fragment() ) {
			$url .= $this->get_fragment();
		}

		return $url;
	}

	public static function create_with_path( $path ) {
		return new self( $path );
	}

	public function __toString() {
		return $this->get_url();
	}

}