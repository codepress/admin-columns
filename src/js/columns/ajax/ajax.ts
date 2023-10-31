import axios, {AxiosPromise} from "axios";
import {ListScreenColumnData, ListScreenColumnsData, ListScreenData} from "../../types/requests";
import {MappedListScreenData} from "../../types/admin-columns";

declare const ajaxurl: string;

export type listScreenSettingsResponse = {
    data: {
        list_screen_data: {
            version: string,
            list_screen: ListScreenData
        },
        settings: any
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

export const saveListScreen = (data: MappedListScreenData) => {
    const formData = new FormData();
    let columns: ListScreenColumnsData = {};

    data.columns.forEach( c => {
        columns[ c.name ] = c;
    })

    let listScreenData = Object.assign( data, {
        columns: columns
    } )

    formData.set('action', 'ac-list-screen-settings')
    formData.set('method', 'save_settings');
    formData.set('data', JSON.stringify(listScreenData));

    axios.post(ajaxurl,
        formData
    )

}