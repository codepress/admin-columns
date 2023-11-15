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

declare namespace AC.Vars.Admin.Settings {

    type ColumnSettingCollection = ColumnSetting[]
    type ColumnSetting = AbstractColumnSetting;

    interface AbstractColumnSetting {
        type: string
    }

}

declare const ac_admin_columns: AcAdminColumnsVar;

