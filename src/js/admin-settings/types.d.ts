declare const ac_settings_i18n: LocalizedSettingsI18n;
declare const ac_settings: InlineVarAcSettings;

type InlineVarAcSettings = {
    _ajax_nonce: string
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