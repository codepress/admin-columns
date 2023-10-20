import axios from "axios";

declare const ajaxurl: string;


export const getListScreenSettings = (listId: string) => {
    return axios.get(ajaxurl, {
        params: {
            action: 'ac-list-screen-settings',
            list_screen_id: listId,
            method: 'get_settings'
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