import {keyAnyPair, keySpecificPair} from "../helpers/types";

export type LocalizedAcColumnSettings = {
    _ajax_nonce: string,
    i18n: any
    layout: string,
    list_screen: string,
    uninitialized_list_screens: UninitializedListScreens
    original_columns: Array<string>
}

export type LocalizedAcTableI18n = {
    value_loading: string;
    edit: string;
    download: string;
}

export type LocalizedAcAddonsi18n = { [key: string]: string }

export type LocalizedAcAddonSettings = {
    _ajax_nonce: string,
    is_network_admin: boolean
}

export type LocalizedAcGeneralSettings = {
    _ajax_nonce: string
}

export type AcGeneralSettingsI18N = {
    restore_settings: string
}


export type UninitializedListScreens = keySpecificPair<UninitializedListScreen>

export type UninitializedListScreen = {
    label: string,
    screen_link: string
}

export type ListScreenStorageType = {
    columns: keyAnyPair,
    settings: keyAnyPair
    list_screen: string,
    list_screen_id: string,
    title: string
}

export type ValueModalItem = {
    element: HTMLElement,
    title: string | null,
    editLink: string,
    downloadLink: string,
    columnName: string,
    objectId: number
}

export type ValueModalItemCollection = Array<ValueModalItem>