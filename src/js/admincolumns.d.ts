import Table from "./table/table";
import ScreenOptionsColumns from "./table/screen-options-columns";
import Tooltips from "./table/tooltips";
import {AddonDownload} from "./modules/addon-download";
import Nanobus = require("nanobus");
import Modals from "./modules/modals";

export interface AdminColumnsInterface {
    events: Nanobus,
    Form?: any,
    Table?: Table,
    ScreenOptionsColumns?: ScreenOptionsColumns
    Tooltips?: Tooltips
    Modals: Modals
    Addons?: { [key: string]: AddonDownload }
}

export interface LocalizedScriptAC {
    layout: string,
    list_screen: string,
    _ajax_nonce: string,
    table_id: string,
    column_types: { [key: string]: string }
}

export interface LocalizedScriptACTable extends LocalizedScriptAC {
    ajax_nonce: string,
    list_screen_link: string,
    meta_type: string
}