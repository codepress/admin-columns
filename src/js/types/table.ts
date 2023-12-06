import {keyStringPair} from "../helpers/types";

export type LocalizedAcTable = {
    assets: string,
    ajax_nonce: string,
    column_types: keyStringPair
    layout: string,
    list_screen: string,
    list_screen_link: string,
    meta_type: string,
    read_only: boolean
    screen: string
    table_id: string,
    current_user_id: number
}