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
    settings: {
        label: {
            select_label: string
        }
    }
    editor: {
        label: {
            add_column: string
            add_columns: string
            load_default_columns: string
            clear_columns: string
            save: string
            view: string
        }
        sentence: {
            show_default_columns: string
            get_started: string
            documentation: string
        }
    }
}

declare const ac_admin_columns_i18n: AdminColumnsI18n;

export const getColumnSettingsTranslation = () => {
    return ac_admin_columns_i18n;
}