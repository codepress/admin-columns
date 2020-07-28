import Table from "./table/table";

export interface AdminColumnsInterface {
    events: nanoBusInterface,
    Form: any,
    Table?: Table
}

export interface LocalizedScriptAC {
    layout: string,
    list_screen: string,
    _ajax_nonce: string,
    column_types: { [key: string]: string }
}