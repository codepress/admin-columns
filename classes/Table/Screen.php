<?php

namespace AC\Table;

use AC;
use AC\Asset;
use AC\Asset\Location\Absolute;
use AC\Capabilities;
use AC\ListScreen;
use AC\Registerable;
use AC\Renderable;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;

final class Screen implements Registerable
{

	private Absolute $location;

	private AC\TableScreen $table_screen;

	private ?AC\ListScreen $list_screen;

	private array $screen_options = [];

	private AC\Settings\GeneralOption $option_storage;

	public function __construct(
			AC\AdminColumns $plugin,
			TableScreen $table_screen,
			AC\Settings\GeneralOption $option_storage,
			?ListScreen $list_screen = null
	) {
		$this->location = $plugin->get_location();
		$this->table_screen = $table_screen;
		$this->list_screen = $list_screen;
		$this->option_storage = $option_storage;
	}

	public function register(): void
	{
		(new AC\Table\AdminHeadStyle())->register();

		add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
		add_filter('admin_body_class', [$this, 'admin_class']);
		add_action('admin_footer', [$this, 'render_actions']);
		add_filter('screen_settings', [$this, 'screen_options']);
	}

	public function get_list_screen(): ?ListScreen
	{
		return $this->list_screen;
	}

	private function show_edit_columns_action(): bool
	{
		if ( ! current_user_can(Capabilities::MANAGE)) {
			return false;
		}

		return (new AC\Storage\Repository\EditButton($this->option_storage))->is_active();
	}

	public function render_actions(): void
	{
		?>
		<div id="ac-table-actions" class="ac-table-actions">
			<div class="ac-table-actions-buttons">
			</div>
		</div>
		<?php
	}

	public function register_screen_option(Renderable $option): void
	{
		$this->screen_options[] = $option;
	}

	public function screen_options($html): string
	{
		if (empty($this->screen_options)) {
			return (string)$html;
		}

		ob_start();
		?>

		<fieldset class='acp-screen-option-prefs'>
			<legend><?= __('Admin Columns', 'codepress-admin-columns'); ?></legend>
			<div class="acp-so-container">
				<?php

				foreach ($this->screen_options as $option) {
					echo $option->render();
				}

				?>
			</div>
		</fieldset>

		<?php

		$html .= ob_get_clean();

		return $html;
	}

	private function get_edit_columns_url(): string
	{
		return EditorUrlFactory::create(
				$this->table_screen->get_id(),
				$this->table_screen->is_network(),
				$this->list_screen ? $this->list_screen->get_id() : null
		);
	}

	public function admin_scripts(): void
	{
		$style = new Asset\Style(
				'ac-table',
				$this->location->with_suffix('assets/css/table.css'),
				['ac-ui', 'ac-material-symbols']
		);
		$style->enqueue();

		$edit_columns_translation = __('Edit columns', 'codepress-admin-columns');
		$table_translation = Asset\Script\Localize\Translation::create([
				'value_loading'        => __('Loading...', 'codepress-admin-columns'),
				'edit'                 => __('Edit', 'codepress-admin-columns'),
				'view'                 => __('View', 'codepress-admin-columns'),
				'download'             => __('Download', 'codepress-admin-columns'),
				'edit_columns'         => $edit_columns_translation,
				'edit_columns_tooltip' => sprintf(
						__("Show %s button on table screen.", 'codepress-admin-columns'),
						$edit_columns_translation
				),
		]);

		$script = new Asset\Script(
				'ac-table',
				$this->location->with_suffix('assets/js/table.js'),
				['jquery', Asset\Script\GlobalTranslationFactory::HANDLE]
		);

		$args = [
				'layout'            => '',
				'column_types'      => '',
				'read_only'         => false,
				'assets'            => $this->location->with_suffix('assets/')->get_url(),
				'list_screen'       => (string)$this->table_screen->get_id(),
				'ajax_nonce'        => wp_create_nonce('ac-ajax'),
				'table_id'          => $this->table_screen->get_attr_id(),
				'screen'            => $this->table_screen->get_screen_id(),
				'list_screen_link'  => $this->get_list_screen_clear_link(),
				'show_edit_columns' => $this->show_edit_columns_action(),
				'edit_columns_url'  => $this->get_edit_columns_url(),
				'current_user_id'   => get_current_user_id(),
				'number_format'     => [
						'decimal_point' => $this->get_local_number_format('decimal_point'),
						'thousands_sep' => $this->get_local_number_format('thousands_sep'),
				],
				'meta_type'         => $this->table_screen instanceof AC\TableScreen\MetaType
						? (string)$this->table_screen->get_meta_type()
						: '',
		];

		if ($this->list_screen) {
			$args['column_types'] = $this->get_column_types_mapping();
			$args['layout'] = (string)$this->list_screen->get_id();
			$args['read_only'] = $this->list_screen->is_read_only();

			do_action('ac/table_scripts', $this->list_screen, $this);
		}

		$script
				->add_inline_variable('ac_table', $args)
				->localize('ac_table_i18n', $table_translation)
				->enqueue();
	}

	public function admin_class($classes): string
	{
		$classes .= ' ac-' . $this->table_screen->get_id();

		return (string)apply_filters('ac/table/body_class', $classes, $this);
	}

	private function get_column_types_mapping(): array
	{
		$types = [];
		foreach ($this->list_screen->get_columns() as $column) {
			$types[(string)$column->get_id()] = $column->get_type();
		}

		return $types;
	}

	private function get_local_number_format(string $var)
	{
		global $wp_locale;

		return $wp_locale->number_format[$var] ?? null;
	}

	private function get_list_screen_clear_link(): string
	{
		$url = $this->list_screen
				? $this->list_screen->get_table_url()
				: $this->table_screen->get_url();

		$query_args_whitelist = [
				'layout',
				'orderby',
				'order',
		];

		switch (true) {
			case $this->table_screen instanceof AC\PostType :
				$query_args_whitelist[] = 'post_status';
				break;
			case $this->table_screen instanceof AC\TableScreen\User :
				$query_args_whitelist[] = 'role';
				break;
			case $this->table_screen instanceof AC\TableScreen\Comment :
				$query_args_whitelist[] = 'comment_status';
				break;
		}

		foreach ($query_args_whitelist as $query_arg) {
			if (isset($_GET[$query_arg]) && is_string($_GET[$query_arg])) {
				$url = $url->with_arg($query_arg, $_GET[$query_arg]);
			}
		}

		return (string)$url;
	}

}