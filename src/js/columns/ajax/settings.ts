import axios, {AxiosPromise} from "axios";
import {getColumnSettingsConfig} from "../utils/global";
import JsonSuccessResponse = AC.Ajax.JsonSuccessResponse;
import JsonDefaultFailureResponse = AC.Ajax.JsonDefaultFailureResponse;
import {SvelteSelectItem} from "../../types/select";
import {mapDataToFormData} from "../../helpers/global";
import {nonceParam} from "../../helpers/nonce";

declare const ajaxurl: string;

type remoteSelectOptionsPayload = {
    options: Array<SvelteSelectItem>
}
type RemoteSelectOptionsResponse = JsonSuccessResponse<remoteSelectOptionsPayload>|JsonDefaultFailureResponse

export const getRemoteSelectOptions = (handler: string, data: Object): AxiosPromise<RemoteSelectOptionsResponse> => {
    let params = Object.assign( {}, data,{
        ...nonceParam(getColumnSettingsConfig().nonce),
        action: handler,
    });

    return axios.get(ajaxurl,{
        params
    })

}

export const persistScreenOptions = ( name: string, value : any) => {
    return axios.post(ajaxurl, mapDataToFormData({
        action: 'ac-admin-screen-options',
        ...nonceParam(getColumnSettingsConfig().nonce),
        option_name: name,
        option_value: value
    }))
}