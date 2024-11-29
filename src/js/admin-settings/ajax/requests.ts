import axios, {AxiosPromise} from "axios";

declare const ajaxurl: string;

type PersistGeneralOptionArgs = {
    nonce: string
    name: string
    value: string
}

export const persistGeneralOption = (args: PersistGeneralOptionArgs): AxiosPromise<''> => {
    const data = new FormData()
    data.append('action', 'ac-persist-admin-general-option');
    data.append('_ajax_nonce', args.nonce);
    data.append('option_name', args.name);
    data.append('option_value', args.value);

    return axios.post(ajaxurl, data);
}


type GetGeneralOptionArgs = {
    nonce: string
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
            _ajax_nonce: args.nonce,
            option_name: args.name,
        }
    })
}

type RestoreSettingsArgs = {
    nonce: string
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
    data.append('_ajax_nonce', nonce);
    data.append('action', 'ac-restore-settings');

    return axios.post(ajaxurl, data);
}