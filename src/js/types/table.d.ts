import {keySpecificPair, keyStringPair} from "./helpers/types";

export type LocalizedAcTable = {
    ajax_nonce: string,
    column_types: keyStringPair
    column_widths: keySpecificPair<WidthType>
    layout: string,
    list_screen: string,
    list_screen_link: string,
    meta_type: string,
    screen: string
    table_id: string,
}

export type WidthType = {
    width: number,
    width_unit: string
}