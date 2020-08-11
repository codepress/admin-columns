import Table from "./table/table";
import ScreenOptionsColumns from "./table/screen-options-columns";
import Tooltips from "./table/tooltips";
import {AddonDownload} from "./modules/addon-download";
import Nanobus = require("nanobus");

export interface AdminColumnsInterface {
    events: Nanobus,
    Form?: any,
    Table?: Table,
    ScreenOptionsColumns?: ScreenOptionsColumns
    Tooltips?: Tooltips
    Addons?: { [key: string]: AddonDownload }
}

export interface LocalizedScriptAC {
    layout: string,
    list_screen: string,
    _ajax_nonce: string,
    table_id: string,
    column_types: { [key: string]: string }
}