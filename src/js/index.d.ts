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


declare namespace AC.Column.Settings {
    import Rule = AC.Specification.Rule;
    type ColumnSettingCollection = ColumnSetting[]
    type ColumnSetting = AbstractColumnSetting;
    type SettingOption = { value: string, label: string, group: string | null }

    interface AbstractColumnSetting<Type = string, Name = string> {
        name: Name
        label: string
        description: string
        input: {
            type: Type
            default?: any
            append?: string
        }
        children?: ColumnSettingCollection
        is_parent?: boolean
        conditions?: Rule
    }

    type LabelSetting = AbstractColumnSetting<'label'>;
    type TextSetting = AbstractColumnSetting<'text'>;

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

    interface RemoteSelectSetting extends SelectSetting {
        input: {
            type: 'remote_options'
            data: {
                ajax_handler: string
            }
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
        children: [
            AbstractColumnSetting<'width', 'width'>,
            ToggleSetting<'width_unit'>
        ]
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