declare const ac_settings_i18n: LocalizedSettingsI18n;
declare const ac_settings: InlineVarAcSettings;

type InlineVarAcSettings = {
    _ajax_nonce: string
    is_pro: boolean
    upgrade_url: string
    features: Array<{ url: string, label: string }>
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
    upgrade_to_pro_subtitle: string
    view_all_features: string
    upgrade_button: string
}