import axios, {AxiosPromise} from "axios";
import {NonceData} from "../../types/requests";
import {nonceParam} from "../../helpers/nonce";

declare const ajaxurl: string;

type PersistGeneralOptionArgs = {
    nonce: NonceData
    name: string
    value: string
}

export const persistGeneralOption = (args: PersistGeneralOptionArgs): AxiosPromise<''> => {
    const data = new FormData()
    data.append('action', 'ac-persist-admin-general-option');
    data.append(args.nonce.name, args.nonce.value);
    data.append('option_name', args.name);
    data.append('option_value', args.value);

    return axios.post(ajaxurl, data);
}


type GetGeneralOptionArgs = {
    nonce: NonceData
    name: string
}

type getGeneralOptionResponse = {
    success: boolean;
    data: any
}

export const getGeneralOption = (args: GetGeneralOptionArgs): AxiosPromise<getGeneralOptionResponse> => {
    return axios.get(ajaxurl, {
        params: {
            action: 'ac-get-admin-general-option',
            ...nonceParam(args.nonce),
            option_name: args.name,
        }
    })
}

type RestoreSettingsArgs = {
    nonce: NonceData
}


type RestoreSettingsArgsResponseSuccess = {
    success: true;
    data: {
        message: string;
    }
}
type RestoreSettingsArgsResponseError = {
    success: true;
    data: {
        message: string;
    }
}

export const restoreSettings = ({nonce}: RestoreSettingsArgs): AxiosPromise<RestoreSettingsArgsResponseSuccess|RestoreSettingsArgsResponseError> => {
    let data = new FormData();
    data.append(nonce.name, nonce.value);
    data.append('action', 'ac-restore-settings');

    return axios.post(ajaxurl, data);
}