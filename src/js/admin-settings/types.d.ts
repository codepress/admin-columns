declare const ac_settings_i18n: LocalizedSettingsI18n;
declare const ac_settings: InlineVarAcSettings;

type InlineVarAcSettings = {
    _ajax_nonce: string
    is_pro: boolean
    assets: string
    upgrade_panel: {
        upgrade_url: string
        badge: string
        title: string
        subtitle: string
        button: string
        view_all: string
        trust: string
        feature_groups: Array<{
            title: string
            features: string[]
        }>
    } | null
}

type LocalizedSettingsI18n = {
    settings: string
    general_settings: string
    general_settings_description: string
    show_x_button: string
    edit_button: string
    settings_saved_successful: string
    restore_settings: string
    restore_settings_description: string
    restore_settings_warning: string
}