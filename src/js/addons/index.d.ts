declare const ac_addons_i18n: LocalizedAddonsI18n;
declare const ac_addons: InlineVarAcAddons;

type InlineVarAcAddons = {
    nonce: import('../types/requests').NonceData
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
    subtitle: string,
    buy_now: string,
    title: {
        page: string
        enabled: string
        recommended: string
        available: string
    }
}