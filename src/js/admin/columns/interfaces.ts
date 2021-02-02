import {AdminColumnsBaseInterface, LocalizedScriptAC} from "../../admincolumns";
import {Form} from "./form";
import {keyAnyPair, keySpecificPair} from "../../helpers/types";

export interface AdminColumnSettingsInterface extends AdminColumnsBaseInterface {
    Form: Form
}

export interface LocalizedScriptColumnSettings extends LocalizedScriptAC {
    uninitialized_list_screens: UninitializedListScreens
    original_columns: Array<string>
    i18n: any
}

export type UninitializedListScreens = keySpecificPair<UninitializedListScreen>

export type UninitializedListScreen = {
    label: string,
    screen_link: string
}

export type ListScreenStorageType = {
    columns: keyAnyPair,
    settings: keyAnyPair
    list_screen: string,
    list_screen_id: string,
    title: string
}