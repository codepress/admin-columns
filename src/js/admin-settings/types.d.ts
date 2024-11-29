declare const AC_SETTINGS_I18N: LocalizedSettingsI18n;
declare const AC_SETTINGS: InlineVarAcSettings;

type InlineVarAcSettings = {
    _ajax_nonce: string
}

type LocalizedSettingsI18n = {
    settings: string
    general_settings: string
    general_settings_description: string
    show_x_button: string
    edit_button: string
    restore_settings: string
    restore_settings_description: string
    restore_settings_warning: string
}