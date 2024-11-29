declare const AC_ADDONS_I18N: LocalizedAddonsI18n;
declare const AC_ADDONS: InlineVarAcAddons;

type InlineVarAcAddons = {
    _ajax_nonce: string
    is_network_admin: boolean
    asset_location: string
    pro_installed: boolean
    buy_url: boolean
}

type LocalizedAddonsI18n = {
    plugin_installed: string
    title: {
        enabled: string
        recommended: string
        available: string
    }
}