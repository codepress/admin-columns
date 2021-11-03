import axios, {AxiosPromise} from "axios";
import {mapDataToFormData} from "../helpers/global";

declare const ajaxurl: string;

type DownloadResponse = DownloadSuccessResponse | DownloadFailureResponse

type DownloadSuccessResponse = {
    data: {
        activated: boolean,
        status: string
    },
    success: true
}

type DownloadFailureResponse = {
    success: false,
    data: string
}

export default class AddonDownloader {

    network_admin: boolean
    slug: string
    nonce: string

    constructor(slug: string, network_admin: boolean, nonce: string) {
        this.slug = slug;
        this.network_admin = network_admin;
        this.nonce = nonce;
    }

    download(): AxiosPromise<DownloadResponse> {
        return axios.post(ajaxurl, mapDataToFormData({
            action: 'acp-ajax-install-addon',
            plugin_name: this.slug,
            network_wide: this.network_admin ? 1 : 0,
            _ajax_nonce: this.nonce
        }));
    }


}