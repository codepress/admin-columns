import Table from "./table/table";
import ScreenOptionsColumns from "./table/screen-options-columns";
import Tooltips from "./table/tooltips";
import {AddonDownload} from "./modules/addon-download";
import Modals from "./modules/modals";
import {keySpecificPair, keyStringPair} from "./helpers/types";
import Nanobus = require("nanobus");

export interface AdminColumnsBaseInterface {
    events: Nanobus,
    Modals: Modals
}

export interface AdminColumnsInterface extends AdminColumnsBaseInterface {
    Form?: any,
    Table?: Table,
    ScreenOptionsColumns?: ScreenOptionsColumns
    Tooltips?: Tooltips
    Addons?: keySpecificPair<AddonDownload>
}

export interface LocalizedScriptAC {
    layout: string,
    list_screen: string,
    _ajax_nonce: string,
    table_id: string,
    column_types: keyStringPair
}

export interface LocalizedScriptACTable extends LocalizedScriptAC {
    ajax_nonce: string,
    list_screen_link: string,
    meta_type: string,
    column_widths: keySpecificPair<WidthType>
    screen: string
}

export type WidthType = {
    width: number,
    width_unit: string
}