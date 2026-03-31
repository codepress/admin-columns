import axios, {AxiosPromise} from "axios";
import {getAddonsConfig} from "../global";

declare const ajaxurl: string;


export interface SiteContext {
    field_group_count?: number;
    post_type_count?: number;
    product_count?: number;
}

export interface IntegrationItem {
    plugin_active: boolean
    title: string
    external_link: string
    slug: string
    active: boolean
    description: string
    plugin_logo: string
    plugin_link: string
    priority: number
    site_context: SiteContext | null
}

type FetchIntegrationsResponse = {
    success: true;
    data: {
        integrations: Array<IntegrationItem>
    }
}

type ToggleIntegrationStatusResponse = {
    success: true;
    data: string
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

export const toggleIntegrationStatus = (args: ToggleIntegrationStatusArgs): AxiosPromise<ToggleIntegrationStatusResponse> => {
    const data = new FormData()
    data.append('action', 'ac-integration-toggle');
    data.append('integration', args.integration);
    data.append('_ajax_nonce', getAddonsConfig()._ajax_nonce);

    if (args.status) {
        data.append('status', '1');
    }

    return axios.post(ajaxurl, data);
}