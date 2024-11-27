import axios, {AxiosPromise} from "axios";

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
