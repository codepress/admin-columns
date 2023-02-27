import axios from "axios";
import {mapDataToFormData} from "../helpers/global";

import {LocalizedAcGeneralSettings} from "../types/admin-columns";

declare const ajaxurl: string;
declare const AC: LocalizedAcGeneralSettings;

export const persistGeneralSetting = ( name: string, value: string ) => {
    return axios.post(ajaxurl, mapDataToFormData({
        action: 'ac_admin_general_options',
        _ajax_nonce: AC._ajax_nonce,
        option_name: name,
        option_value: value
    }));
}

