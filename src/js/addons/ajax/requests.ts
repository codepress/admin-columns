import axios, {AxiosPromise} from "axios";

declare const ajaxurl: string;


export interface IntegrationItem {
    plugin_active: boolean
    title: string
    external_link: string
    slug: string
    active: string
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
    const data = new FormData()
    data.append('action', 'acp-integration-toggle');
    data.append('integration', args.integration);
    data.append('_ajax_nonce', AC_ADDONS._ajax_nonce);

    if (args.status) {
        data.append('status', '1');
    }

    return axios.post(ajaxurl, data);
}