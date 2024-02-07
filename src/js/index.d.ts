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

    type AcAdminColumnsVar = {
        nonce: string
        column_groups: ColumnGroup[]
        column_types: ColumnConfig[]
        menu_groups_opened: string[]
        menu_items: MenuItems
        menu_items_favorites: string[]
        list_key: string
        list_id: string
    }

}

declare namespace AC.Column.Settings.Input {
    interface AbstractSettingInput<Type = string > {
        type: Type
        name: string
        default: string
    }
    document.querySelector()
}

declare namespace AC.Column.Settings {
    import Rule = AC.Specification.Rule;
    import AbstractSettingInput = AC.Column.Settings.Input.AbstractSettingInput;
    type ColumnSettingCollection = ColumnSetting[]
    type ColumnSetting = AbstractColumnSetting;
    type SettingOption = { value: string, label: string, group: string | null }


    interface AbstractColumnSetting<Input = AbstractSettingInput> {
        type: string,
        attributes: {
            label: string
            description?: string,
            [key:string] : any
        }
        conditions: Rule
        input: Input,
        children?: ColumnSettingCollection
        is_parent?: boolean
    }

    type LabelSetting = AbstractColumnSetting;
    type TextSetting = AbstractColumnSetting;

    interface DateFormatSetting extends AbstractColumnSetting {
        input: {
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
            options: SettingOption[]
        }
    }

    interface WidthSetting extends AbstractColumnSetting {
        name: 'width'
        children: AbstractSettingInput[]
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