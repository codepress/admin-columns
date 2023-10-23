import {keyAnyPair, keySpecificPair} from "../helpers/types";
import {ListScreenColumnData, ListScreenData} from "./requests";

export type ModuleConfirmationTranslation = {
    ok: string
    cancel: string
}

export type LocalizedAcColumnSettingsI18n = {
    value: string
    label: string
    clone: string
    error: string
    errors: {
        loading_column: string
        save_settings: string
    }
}

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

export type LocalizedAcColumnSettings = {
    _ajax_nonce: string
    i18n: LocalizedAcColumnSettingsI18n
    layout: string
    list_screen: string
    uninitialized_list_screens: UninitializedListScreens
    original_columns: Array<string>
    column_groups: ColumnGroup[]
    column_types: ColumnConfig[]
}

export type LocalizedAcTableI18n = {
    ok: string
    cancel: string
    value_loading: string
    edit: string
    download: string
    confirmation: ModuleConfirmationTranslation
}

export type LocalizedAcAddonsi18n = { [key: string]: string }

export type LocalizedAcAddonSettings = {
    _ajax_nonce: string
    is_network_admin: boolean
}

export type LocalizedAcGeneralSettings = {
    _ajax_nonce: string
}

export type AcGeneralSettingsI18N = {
    restore_settings: string
    confirmation: ModuleConfirmationTranslation
}

export type UninitializedListScreens = keySpecificPair<UninitializedListScreen>

export type UninitializedListScreen = {
    label: string
    screen_link: string
}

export type ListScreenStorageType = {
    columns: keyAnyPair
    settings: keyAnyPair
    list_screen: string
    list_screen_id: string
    title: string
}

export type ValueModalItem = {
    element: HTMLElement
    title: string | null
    editLink: string
    downloadLink: string
    columnName: string
    objectId: number
}

export type ValueModalItemCollection = Array<ValueModalItem>

export type MappedListScreenData = Omit<ListScreenData, 'columns'> & { columns: ListScreenColumnData[] }