import {keyAnyPair, keySpecificPair} from "../helpers/types";

export type ModuleConfirmationTranslation = {
    ok: string
    cancel: string
}

export type LocalizedAcTableI18n = {
    ok: string
    cancel: string
    value_loading: string
    edit: string
    view: string
    download: string
    confirmation: ModuleConfirmationTranslation
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
    viewLink: string
    columnName: string
    objectId: number
    view: string
    params: { [key: string]: any }
}

export type ValueModalItemCollection = Array<ValueModalItem>