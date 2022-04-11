import {Column} from "./column";
import {keyStringPair} from "../../helpers/types";
import AcServices from "../../modules/ac-services";
import {Form} from "./form";
import {ListScreenStorageType, LocalizedAcColumnSettings} from "../../types/admin-columns";

const axios = require('axios');

declare const ajaxurl: string;
declare const AC: LocalizedAcColumnSettings;
declare const AC_SERVICES: AcServices

export interface ColumnSettingsResponse {
    success: boolean
    data: {
        list_id: string,
        message: string
    }
}

const mapDataToFormData = (data: keyStringPair, formData: FormData = new FormData()): FormData => {
    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });

    return formData;
}

export const submitColumnSettings = (data: ListScreenStorageType) => {
    return axios.post(ajaxurl, mapDataToFormData({
        action: 'ac-columns',
        id: 'save',
        _ajax_nonce: AC._ajax_nonce,
        data: JSON.stringify(data)
    }));
}

export const switchColumnType = (type: string, list_screen: string = AC.list_screen) => {
    return axios.post(ajaxurl, mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        current_original_columns: JSON.stringify(AC_SERVICES.getService<Form>('Form')?.getOriginalColumns().map((e: Column) => e.getName())),
        id: 'select',
        list_screen: list_screen,
        type: type,
    }));
}

export const refreshColumn = (name: string, data: string, list_screen: string = AC.list_screen) => {
    return axios.post(ajaxurl, mapDataToFormData({
        _ajax_nonce: AC._ajax_nonce,
        action: 'ac-columns',
        column_name: name,
        data: data,
        id: 'refresh',
        list_screen: list_screen,
    }));
}
