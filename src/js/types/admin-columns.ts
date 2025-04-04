export type ModuleConfirmationTranslation = {
    ok: string
    cancel: string
}

export type LocalizedAcGeneralSettings = {
    _ajax_nonce: string
}

export type AcGeneralSettingsI18N = {
    restore_settings: string
    confirmation: ModuleConfirmationTranslation
}

export type UninitializedListScreens = Record<string, UninitializedListScreen>

export type UninitializedListScreen = {
    label: string
    screen_link: string
}

export type ListScreenStorageType = {
    columns: Record<string, any>
    settings: Record<string, any>
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