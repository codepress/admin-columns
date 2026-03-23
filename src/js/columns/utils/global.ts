export const getColumnSettingsConfig = () => {
    return ac_admin_columns;
}

type FeatureTranslation = {
    badge: string
    headline: string
    description: string
    label: string,

}

type AdminColumnsI18n = {
    errors: {
        ajax_unknown: string
        original_exist: string
    }
    pro: {
        modal: {
            also_get: string
            features: Record<string, FeatureTranslation>[]
            guarantee: string
            see_all: string
            subtitle: string
            title: string
            trusted_by: string
            upgrade: string
        }
        banner: {
            badge: string
            description: string
            discount_description: string
            discount_note: string
            discount_title: string
            features_label: string
            guarantee: string
            see_all: string
            send_discount: string
            title: string
            trust: string
            upgrade_cta: string
            works_with: string
            your_email: string
        }
        settings: {
            status: {
                activate: string
                status: string
                description: string
            }
            conditionals: {
                conditionals: string
                description: string
                select_roles: string
                select_users: string
            }
            elements: {
                table_elements: string
                description: string
                features: string
                default: string
            }
            preferences: {
                preferences: string
                description: string
                horizontal_scrolling: string
                sorting: string
                primary_column: string
                wrapping: string
                wrapping_options: {
                    wrap: string
                    clip: string
                }
                segments: string
                no_segments: string
                unlock: string
            }
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
            column: string
            column_info: string
            table_view_label: string
            select_icon: string
        }
    }
    editor: {
        label: {
            add_column: string
            add_columns: string
            load_default_columns: string
            clear_columns: string
            undo: string
            save: string
            view: string
        }
        sentence: {
            columns_read_only: string
            column_no_duplicate: string
            original_already_exists: string
            show_default_columns: string
            get_started: string
            documentation: string
        }
    },
    notices: {
        unsaved_changes: string
        unsaved_changes_leave: string
        not_saved_settings: string
        inactive: string
    }
    review: {
        all_good: string
        check_pro: string
        checkdocs: string
        docs: string
        forum: string
        give_rating: string
        glad: string
        happy: string
        help_improve: string
        need_feature: string
        rate: string
        tweet: string
        whats_wrong: string
    },
    support: {
        title: string
    },
}

declare const ac_admin_columns_i18n: AdminColumnsI18n;

export const getColumnSettingsTranslation = () => {
    return ac_admin_columns_i18n;
}