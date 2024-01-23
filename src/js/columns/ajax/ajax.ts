import axios, {AxiosPromise} from "axios";
import {ListScreenData} from "../../types/requests";
import {getColumnSettingsConfig} from "../utils/global";
import ColumnConfig = AC.Vars.Admin.Columns.ColumnConfig;


declare const ajaxurl: string;

export type listScreenSettingsResponse = {
    data: {
        settings: {
            version: string,
            list_screen: ListScreenData
        },
        table_url: string,
        read_only: boolean
        column_settings: { [key: string]: AC.Vars.Settings.ColumnSetting[] }
        column_types: ColumnConfig[]
    },
    success: true
}

export const getListScreenSettings = (listKey: string, listId: string = ''): AxiosPromise<listScreenSettingsResponse> => {
    const nonce = getColumnSettingsConfig().nonce;

    return axios.get(ajaxurl, {
        params: {
            _ajax_nonce: nonce,
            action: 'ac-list-screen-settings',
            list_key: listKey,
            list_screen_id: listId,
        }
    })
}

export const getColumnSettings = (ListScreen: string, columnType: string) => {
    const nonce = getColumnSettingsConfig().nonce;

    return axios.get(ajaxurl, {
        params: {
            _ajax_nonce: nonce,
            action: 'ac-list-screen-add-column',
            list_screen: ListScreen,
            column_type: columnType
        }
    })
}

export const saveListScreen = (data: ListScreenData, listKey: string) => {
    const nonce = getColumnSettingsConfig().nonce;
    const formData = new FormData();

    formData.set('_ajax_nonce', nonce);
    formData.set('action', 'ac-list-screen-save')
    formData.set('data', JSON.stringify(data));
    formData.set('list_key', listKey);

    return axios.post(ajaxurl,
        formData
    )
}



