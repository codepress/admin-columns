import {AdminColumnSettingsInterface, ListScreenStorageType, LocalizedScriptColumnSettings} from "./interfaces";
import {Column} from "./column";

const axios = require('axios');

declare const ajaxurl: string;
declare const AC: LocalizedScriptColumnSettings;
declare const AdminColumns: AdminColumnSettingsInterface;

export interface ColumnSettingsResponse {
    success: boolean
    data: string
}

export interface ColumnSettingsErrorResponse {
    success: boolean
    data: {
        message: string
    }
}

export const submitColumnSettings = (data: ListScreenStorageType) => {
    let formData = mapDataToFormData({
        action: 'ac-columns',
        id: 'save',
        _ajax_nonce: AC._ajax_nonce,
        data: JSON.stringify(data)
    });

    return axios.post(ajaxurl, formData);
}

const mapDataToFormData = (data: { [key: string]: string }, formData: FormData = null): FormData => {
    if (!formData) {
        formData = new FormData();
    }

    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });

    return formData;
}

export const _switchColumnType = (type: string, data: string) => {
    let formData = mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        id: 'select',
        type: type,
        data: data,
        current_original_columns: JSON.stringify(AdminColumns.Form.getOriginalColumns()),
    });

    return axios.post(ajaxurl, formData);
}

export const switchColumnType = (type: string, list_screen: string = AC.list_screen) => {
    let formData = mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        id: 'select',
        type: type,
        list_screen: list_screen,
        current_original_columns: JSON.stringify(AdminColumns.Form.getOriginalColumns().map((e: Column) => e.getName())),
    });

    return axios.post(ajaxurl, formData);
}

export const refreshColumn = (name: string, data: string, list_screen: string = AC.list_screen) => {
    let formData = mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        id: 'refresh',
        column_name: name,
        data: data,
        list_screen: list_screen,
        current_original_columns: JSON.stringify(AdminColumns.Form.getOriginalColumns().map((e: Column) => e.getName())),
    });

    return axios.post(ajaxurl, formData);
}
