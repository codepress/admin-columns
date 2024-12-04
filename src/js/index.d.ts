import AcAdminColumnsVar = AC.Vars.Admin.Columns.AcAdminColumnsVar;


declare namespace AC.Vars.Admin.Columns {

    type ColumnGroup = {
        slug: string
        label: string
        priority: number
    }

    type ColumnConfig = {
        label: string
        value: string
        group: string
        group_key: string
        original: boolean
    }

    type MenuOptions = { [key: string]: string }
    type MenuGroup = {
        options: MenuOptions
        title: string
        icon: string | null
    }
    type MenuItems = { [key: string]: MenuGroup }

    type UninitializedListScreens = { [key: string]: UninitializedListScreen }
    type UninitializedListScreen = {
        label: string
        screen_link: string
    }

    type ProBanner = {
        features: ProBannerFeature[],
        promo?: {
            title: string
            url: string
            button_label: string
            discount_until: string
        }
        promo_url: string
        discount: number
    }
    type ProBannerFeature = {
        url: string
        label: string
    }

    type AcAdminColumnsVar = {
        nonce: string
        is_pro: boolean
        column_groups: ColumnGroup[]
        column_types: ColumnConfig[]
        menu_groups_opened: string[]
        menu_items: MenuItems
        menu_items_favorites: string[]
        uninitialized_list_screens: UninitializedListScreens
        list_key: string
        list_id: string
        urls: [
            upgrade: string
        ],
        pro_banner?: ProBanner
        review: {
            doc_url: string
            upgrade_url: string
        },
        support: {
            description: string
        }
    }

}

declare namespace AC.Column.Settings.Input {
    interface AbstractSettingInput<Type = string> {
        type: Type
        name: string
        default: string
        attributes?: { [key: string]: string }
    }
}

declare namespace AC.Column.Settings {
    import Rule = AC.Specification.Rule;
    import AbstractSettingInput = AC.Column.Settings.Input.AbstractSettingInput;
    type ColumnSettingCollection = ColumnSetting[]
    type ColumnSetting = AbstractColumnSetting;
    type SettingOption = { value: string, label: string, group: string | null }

    interface AbstractColumnSetting {
        type: string,
        label: string,
        description?: string
        attributes: {
            [key: string]: any
        }
        input?: AbstractSettingInput
        conditions?: Rule
        children?: ColumnSettingCollection
        is_parent?: boolean
    }

    interface ColumnInputSetting extends AbstractColumnSetting {
        type: 'row'
        input: AbstractSettingInput
    }

    interface WidthSetting extends AbstractColumnSetting {
        type: 'width'
        input: AbstractSettingInput,
        children: AbstractSettingInput[]
    }

    let t: WidthSetting;

    type LabelSetting = AbstractColumnSetting;


    interface TextSetting extends AbstractColumnSetting {
        input: {
            type: 'date_format'
            default: string,
            placeholder: string
        }
    }

    interface DateFormatSetting extends AbstractColumnSetting {
        input: {
            data: {
                wp_date_format: string
                wp_date_info: string
            }
            type: 'date_format'
            default: string,
            children: [
                {
                    name: 'date_format'
                    input: {
                        type: 'radio',
                        options: SettingOption[]
                    }
                }
            ]
        }
    }

    interface NumberSettings extends AbstractColumnSetting {
        input: {
            type: 'number'
            default: string
            min: string | null
            max: string | null
            step: string
            append?: string
        }
    }

    interface SelectSetting extends AbstractColumnSetting {
        input: {
            type: 'select'
            options: SettingOption[]
            default: string
            attributes: any
        }
    }

    interface SelectSetting extends AbstractColumnSetting {
        input: {
            type: 'select'
            options: SettingOption[]
        }
    }

    interface SelectRemoteSetting extends AbstractColumnSetting {
        input: {
            type: 'select_remote'
            name: string
            default: string,
            options: SettingOption[]
            attributes: {
                'data-handler': string
                'data-params': string
            }
            multiple: false
        }
    }


    interface TypeSetting extends AbstractColumnSetting<'type'> {
        input: {
            options: SettingOption[]
        }
    }

    interface ToggleSetting extends SelectSetting {
        input: {
            type: 'toggle'
            options: SettingOption[],
            attributes?: { [key: string]: string }
        }
    }


}

declare const ac_admin_columns: AcAdminColumnsVar;

declare namespace AC.Ajax {
    interface JsonResponse {
        success: boolean,
        data: any
    }

    interface JsonSuccessResponse<T = any> extends JsonResponse {
        success: true,
        data: T
    }

    interface JsonDefaultFailureResponse extends JsonResponse {
        success: false,
        data: {
            message: string
        }
    }
}