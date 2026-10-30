import {ModuleConfirmationTranslation} from "./admin-columns";
import {NonceData} from "./requests";

export type LocalizedAcTable = {
    assets: string,
    ajax_nonce: string,
    table_rows_nonce: NonceData,
    column_types: Record<string, string>,
    layout: string,
    list_screen: string,
    list_screen_link: string,
    edit_columns_url: string,
    meta_type: string,
    read_only: boolean
    show_edit_columns: string
    screen: string
    table_id: string,
    current_user_id: number
}

export type LocalizedAcTableI18n = {
    ok: string
    cancel: string
    value_loading: string
    edit: string
    view: string
    download: string
    confirmation: ModuleConfirmationTranslation
    edit_columns: string
}