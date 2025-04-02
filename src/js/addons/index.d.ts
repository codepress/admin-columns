declare const ac_addons_i18n: LocalizedAddonsI18n;
declare const ac_addons: InlineVarAcAddons;

type InlineVarAcAddons = {
    _ajax_nonce: string
    is_network_admin: boolean
    asset_location: string
    pro_installed: boolean
    buy_url: string
}

type LocalizedAddonsI18n = {
    plugin_installed: string,
    plugin_not_detected: string,
    enable_integration: string,
    learn_more: string,
    buy_now: string,
    title: {
        enabled: string
        recommended: string
        available: string
    }
}