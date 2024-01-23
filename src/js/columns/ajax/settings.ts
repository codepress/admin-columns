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

export const getRemoteSelectOptions = (handler: string, list_key: string): AxiosPromise<RemoteSelectOptionsResponse> => {

    return axios.get(ajaxurl,{
        params: {
            _ajax_nonce: getColumnSettingsConfig().nonce,
            action: handler,
            list_key
        }
    })

}