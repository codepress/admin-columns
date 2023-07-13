<?php

namespace AC;

/**
 * Show a notice when plugin dependencies are not met
 */
final class Dependencies
{

    /**
     * @var string
     */
    private $basename;

    /**
     * @var string
     */
    private $version;

    /**
     * Missing dependency messages
     * @var string[]
     */
    private $messages = [];

    public function __construct(string $basename, string $version)
    {
        $this->basename = $basename;
        $this->version = $version;
    }

    public function get_basename(): string
    {
        return $this->basename;
    }

    public function get_version(): string
    {
        return $this->version;
    }

    private function register(): void
    {
        add_action('after_plugin_row_' . $this->basename, [$this, 'display_notice'], 5);
        add_action('admin_head', [$this, 'display_notice_css']);
    }

    public function add_missing(string $message, string $key): void
    {
        if ( ! $this->has_missing()) {
            $this->register();
        }

        $this->messages[$key] = $this->sanitize_message($message);
    }

    public function add_missing_plugin(string $plugin, string $url = null, string $version = null): void
    {
        $this->add_missing(
            $this->get_missing_plugin_message($plugin, $url, $version),
            $plugin
        );
    }

    private function get_missing_plugin_message(string $plugin, string $url = null, string $version = null): string
    {
        $plugin = esc_html($plugin);

        if ($url) {
            $plugin = sprintf('<a href="%s">%s</a>', esc_url($url), $plugin);
        }

        if ($version) {
            $plugin .= ' ' . sprintf('version %s+', esc_html($version));
        }

        return sprintf('%s needs to be installed and activated.', $plugin);
    }

    public function has_missing(): bool
    {
        return ! empty($this->messages);
    }

    private function sanitize_message(string $message): string
    {
        return wp_kses($message, [
            'a' => [
                'href'   => true,
                'target' => true,
            ],
        ]);
    }

    public function requires_php(string $version): bool
    {
        if ( ! version_compare(PHP_VERSION, $version, '>=')) {
            $message = sprintf(
                'PHP %s+ is required. Your server currently runs PHP %s. <a href="%s" target="_blank">Learn more about requirements.</a>',
                $version,
                PHP_VERSION,
                esc_url('https://www.admincolumns.com/documentation/getting-started/requirements/')
            );

            $this->add_missing($message, 'PHP Version');

            return false;
        }

        return true;
    }

    /**
     * URL that performs a search in the WordPress repository
     */
    public function get_search_url(string $keywords): string
    {
        return add_query_arg([
            'tab' => 'search',
            's'   => $keywords,
        ], admin_url('plugin-install.php'));
    }

    private function is_plugin_active(): bool
    {
        return is_multisite() && is_network_admin()
            ? is_plugin_active_for_network($this->basename)
            : is_plugin_active($this->basename);
    }

    /**
     * Show a warning when dependencies are not met
     */
    public function display_notice(): void
    {
        $intro = "This plugin can't load because";
        ?>

		<tr class="plugin-update-tr <?= $this->is_plugin_active() ? 'active' : 'inactive'; ?>">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-error notice-alt">
                    <?php
                    if (count($this->messages) > 1)  : ?>
						<p>
                            <?php
                            echo $intro . ':' ?>
						</p>

						<ul>
                            <?php
                            foreach ($this->messages as $message) : ?>
								<li><?php
                                    echo $message; ?></li>
                            <?php
                            endforeach; ?>
						</ul>
                    <?php
                    else : ?>
						<p>
                            <?php
                            echo $intro . ' ' . current($this->messages); ?>
						</p>
                    <?php
                    endif; ?>
				</div>
			</td>
		</tr>

        <?php
    }

    public function display_notice_css(): void
    {
        ?>

		<style>
			.plugins tr[data-plugin='<?php echo $this->basename; ?>'] th,
			.plugins tr[data-plugin='<?php echo $this->basename; ?>'] td {
				box-shadow: none;
			}
		</style>

        <?php
    }

}