import axios, {AxiosPromise} from "axios";
import {getColumnSettingsConfig} from "../utils/global";
import JsonDefaultFailureResponse = AC.Ajax.JsonDefaultFailureResponse;
import JsonSuccessResponse = AC.Ajax.JsonSuccessResponse;

declare const ajaxurl: string;

type menuFavoriteResponse = JsonDefaultFailureResponse | JsonSuccessResponse

export const persistMenuFavorite = (listKey: string, favorite: boolean): AxiosPromise<menuFavoriteResponse> => {
    let data = new FormData();

    data.set('_ajax_nonce', getColumnSettingsConfig().nonce);
    data.set('action', 'ac-editor-menu-favorites');
    data.set('list_key', listKey);
    data.set('status', favorite ? 'favorite' : '')

    return axios.post(ajaxurl, data);
}


export const persistMenuStatus = (group: string, open: boolean): AxiosPromise<JsonDefaultFailureResponse | JsonSuccessResponse> => {
    let data = new FormData();

    data.set('_ajax_nonce', getColumnSettingsConfig().nonce);
    data.set('action', 'ac-editor-menu-status');
    data.set('group', group);
    data.set('status', open ? 'open' : 'close')

    return axios.post(ajaxurl, data);
}