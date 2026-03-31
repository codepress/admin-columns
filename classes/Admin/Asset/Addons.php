<?php

namespace AC\Admin\Asset;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Nonce;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class Addons extends Script
{

    private Nonce\Ajax $nonce;

    private Location $asset_location;

    private bool $is_pro;

    public function __construct(
        string $handle,
        Nonce\Ajax $nonce,
        Location $location,
        Location $asset_location,
        bool $is_pro
    ) {
        parent::__construct($handle, $location, ['jquery']);

        $this->nonce = $nonce;
        $this->asset_location = $asset_location;
        $this->location = $location;
        $this->is_pro = $is_pro;
    }

    public function register(): void
    {
        parent::register();

        $translation = new Script\Localize\Translation([
            'plugin_installed'    => __('The Add-on %s is activated.', 'codepress-admin-columns'),
            'plugin_not_detected' => __('Plugin not detected', 'codepress-admin-columns'),
            'plugin_detected'     => __('Detected on your site', 'codepress-admin-columns'),
            'enable_integration'  => __('Enable Integration', 'codepress-admin-columns'),
            'buy_now'             => __('Unlock with Pro →', 'codepress-admin-columns'),
            'learn_more'          => __('Learn more', 'codepress-admin-columns'),
            'subtitle'            => __('Connect Admin Columns with the plugins you already use. Display, edit, filter, sort, and export their data - all from the list table.', 'codepress-admin-columns'),
            'title'               => [
                'enabled'     => __('Enabled Integrations', 'codepress-admin-columns'),
                'recommended' => __('Recommended Integrations', 'codepress-admin-columns'),
                'available'   => __('More Integrations', 'codepress-admin-columns'),
            ],
            'detected_on_site'     => __('Detected on your site', 'codepress-admin-columns'),
            'recommended'          => __('Recommended', 'codepress-admin-columns'),
            'see_what_you_get'     => __('See what you get', 'codepress-admin-columns'),
            'more_integrations'    => __('More integrations', 'codepress-admin-columns'),
            'install_hint'         => __('Install a supported plugin to see it above', 'codepress-admin-columns'),
            'no_plugins_detected'  => __('Install a supported plugin to get started', 'codepress-admin-columns'),
            'detected_eyebrow'     => __('%d integrations detected on your site', 'codepress-admin-columns'),
            'detected_eyebrow_one' => __('1 integration detected on your site', 'codepress-admin-columns'),
            'page_heading'         => __('Do more with the plugins you already use', 'codepress-admin-columns'),
            'page_subtitle'        => __('Admin Columns Pro adds sorting, filtering, inline editing, and bulk editing to your plugin data — right from the list table.', 'codepress-admin-columns'),
            'your_site'            => __('Your site:', 'codepress-admin-columns'),
        ]);

        $this
            ->localize('ac_addons_i18n', $translation)
            ->add_inline_variable(
                'ac_addons',
                [
                    $this->nonce->get_name() => $this->nonce->create(),
                    'is_network_admin'       => is_network_admin(),
                    'asset_location'         => $this->asset_location->get_url(),
                    'pro_installed'          => $this->is_pro,
                    'buy_url'                => (new UtmTags(new Site(Site::PAGE_PRICING), 'integration'))->get_url(),
                ]
            );
    }

}