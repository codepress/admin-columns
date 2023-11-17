import AcAdminColumnsVar = AC.Vars.Admin.Columns.AcAdminColumnsVar;

declare namespace AC.Vars.Admin.Columns {

    type ColumnGroup = {
        slug: string
        label: string
        priority: number
    }

    type ColumnConfig = {
        label: string
        type: string
        group: string
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
        menu_items: MenuItems
        column_groups: ColumnGroup[]
        column_types: ColumnConfig[]
        list_key: string
        list_screen_id: string
    }

}

declare namespace AC.Column.Settings {

    type ColumnSettingCollection = ColumnSetting[]
    type ColumnSetting = AbstractColumnSetting;
    type SettingOption = { value: string, label: string, group: string | null }

    interface AbstractColumnSetting<Type = string, Name = null> {
        name: Name
        label: string
        description: string
        input: {
            type: Type
        }
    }

    type LabelSetting = AbstractColumnSetting<'label'>;
    type TextSetting = AbstractColumnSetting<'text'>;

    interface SelectSetting extends AbstractColumnSetting {
        input: {
            type: 'select'
            options: SettingOption[]
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

