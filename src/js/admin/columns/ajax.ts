import {LocalizedScriptColumnSettings} from "../../admincolumns";

const axios = require('axios');

declare const ajaxurl: string;
declare const AC: LocalizedScriptColumnSettings;

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

export const submitColumnSettings = (data: string) => {
    let formData = mapDataToFormData({
        action: 'ac-columns',
        id: 'save',
        _ajax_nonce: AC._ajax_nonce,
        data: data
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

export const switchColumnType = (type: string, data: string, originalColumns: Array<string>) => {
    let formData = mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        id: 'select',
        type: type,
        data: data,
        current_original_columns: JSON.stringify(originalColumns),
    });

    return axios.post(ajaxurl, formData);
}
