import axios, {AxiosPromise} from "axios";
import {getColumnSettingsConfig} from "../utils/global";
import JsonSuccessResponse = AC.Ajax.JsonSuccessResponse;
import JsonDefaultFailureResponse = AC.Ajax.JsonDefaultFailureResponse;
import {SvelteSelectItem} from "../../types/select";

declare const ajaxurl: string;

type remoteSelectOptionsPayload = {
    options: Array<SvelteSelectItem>
}
type RemoteSelectOptionsResponse = JsonSuccessResponse<remoteSelectOptionsPayload>|JsonDefaultFailureResponse

export const getRemoteSelectOptions = (handler: string, data: Object): AxiosPromise<RemoteSelectOptionsResponse> => {
    let params = Object.assign( {}, data,{
        _ajax_nonce: getColumnSettingsConfig().nonce,
        action: handler,
    });

    return axios.get(ajaxurl,{
        params
    })

}