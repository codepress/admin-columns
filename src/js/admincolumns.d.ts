import Table from "./table/table";
import ScreenOptionsColumns from "./table/screen-options-columns";
import Nanobus = require("nanobus");
import Tooltips from "./table/tooltips";

export interface AdminColumnsInterface {
    events: Nanobus,
    Form?: any,
    Table?: Table,
    ScreenOptionsColumns?: ScreenOptionsColumns
    Tooltips?: Tooltips
}

export interface LocalizedScriptAC {
    layout: string,
    list_screen: string,
    _ajax_nonce: string,
    table_id : string,
    column_types: { [key: string]: string }
}