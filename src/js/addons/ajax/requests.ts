import axios, {AxiosPromise} from "axios";
import {mapDataToFormData} from "../../helpers/global";

declare const ajaxurl: string;


export interface IntegrationItem {
    plugin_active: boolean
    title: string
    external_link: string
    slug: string
    description: string
    plugin_logo: string
    plugin_link: string
}

type FetchIntegrationsResponse = {
    success: true;
    data: {
        integrations: Array<IntegrationItem>
    }
}

export const fetchIntegrations = (): AxiosPromise<FetchIntegrationsResponse> => {
    return axios.get(ajaxurl,
        {
            params: {
                action: 'ac-integrations',
            }
        }
    )
}

type ToggleIntegrationStatusArgs = {
    integration: string
    status: boolean
}

export const toggleIntegrationStatus = (args: ToggleIntegrationStatusArgs): AxiosPromise<FetchIntegrationsResponse> => {
    return axios.post(ajaxurl,
        mapDataToFormData({
            action: 'acp-integration-toggle',
            integrations: args.integration,
            status: args.status,
            _ajax_nonce: AC_ADDONS._ajax_nonce,
        })
    )
}