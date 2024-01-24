
export const getColumnSettingsConfig = () => {
    return ac_admin_columns;
}


type AdminColumnsI18n = {
    errors: {
        ajax_unknown: string
        original_exist: string
    }
    global: {
        search: string
        select: string
    }
    menu: {
        favorites: string
    }
    settings : {
        label : {
            select_label: string
        }
    }
}

declare const ac_admin_columns_i18n: AdminColumnsI18n;

export const getColumnSettingsTranslation = () => {
    return ac_admin_columns_i18n;
}