export const getColumnSettingsConfig = () => {
    return ac_admin_columns;
}

type AdminColumnsI18n = {
    errors: {
        ajax_unknown: string
        original_exist: string
    }
    pro: {
        modal: {
            title: string
            subtitle: string
            sort_filter: string
            search: string
            bulk_edit: string
            inline_edit: string
            export: string
            list_tables: string
            addon: string
            upgrade: string
        }
        banner: {
            title: string
            title_pro: string
            sub_title: string
            integrations: string
            get_acp: string
            get_percentage_off: string
            submit_email: string
            your_first_name: string
            your_email: string
            send_discount: string
        }
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
            column_no_duplicate: string
            original_already_exists: string
            show_default_columns: string
            get_started: string
            documentation: string
        }
    }
    review: {
        happy: string
        yes: string
        no: string
        glad: string
        give_rating: string
        whats_wrong: string
        checkdocs: string
        docs: string
        forum: string
        rate: string
        tweet: string
        buy: string
    },
    support: {
        title: string
    }
}

declare const ac_admin_columns_i18n: AdminColumnsI18n;

export const getColumnSettingsTranslation = () => {
    return ac_admin_columns_i18n;
}