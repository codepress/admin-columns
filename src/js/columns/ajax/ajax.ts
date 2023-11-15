import axios, {AxiosPromise} from "axios";
import {ListScreenData} from "../../types/requests";


declare const ajaxurl: string;

export type listScreenSettingsResponse = {
    data: {
        list_screen_data: {
            version: string,
            list_screen: ListScreenData
        },
        settings: AC.Vars.Admin.Settings.ColumnSettingCollection
    },
    success: true
}

export const getListScreenSettings = (listId: string): AxiosPromise<listScreenSettingsResponse> => {
    return axios.get(ajaxurl, {
        params: {
            action: 'ac-list-screen-settings',
            list_screen_id: listId,
            method: 'get_settings'
        }
    })
}

export const getListScreenSettingsByListKey = (listKey: string): AxiosPromise<listScreenSettingsResponse> => {
    return axios.get(ajaxurl, {
        params: {
            action: 'ac-list-screen-settings',
            list_key: listKey,
            method: 'get_settings_by_list_key'
        }
    })
}

export const getColumnSettings = (ListScreen: string, columnType: string) => {
    return axios.get(ajaxurl, {
        params: {
            action: 'ac-list-screen-settings',
            method: 'add_column',
            list_screen: ListScreen,
            column_type: columnType
        }
    })
}

export const saveListScreen = (data: ListScreenData) => {
    const formData = new FormData();

    formData.set('action', 'ac-list-screen-settings')
    formData.set('method', 'save_settings');
    formData.set('data', JSON.stringify(data));

    axios.post(ajaxurl,
        formData
    )

}