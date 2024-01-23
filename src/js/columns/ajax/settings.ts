import axios from "axios";
import {getColumnSettingsConfig} from "../utils/global";

declare const ajaxurl: string;


export const getRemoteSelectOptions = (handler: string, list_key: string) => {

    return axios.get(ajaxurl,{
        params: {
            _ajax_nonce: getColumnSettingsConfig().nonce,
            action: handler,
            list_key
        }
    })

}