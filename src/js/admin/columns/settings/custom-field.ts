import {Column} from "../column";
import Nanobus from "nanobus";
// @ts-ignore
import $ from 'jquery';
import {AxiosPromise, AxiosResponse} from "axios";
import {LocalizedAcColumnSettings} from "../../../types/admin-columns";

const axios = require('axios');

declare const AC: LocalizedAcColumnSettings;
declare const ajaxurl: string;
declare global {
    interface Window {
        AC_Requests: any;
    }
}

export const initCustomFieldSelector = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('[data-setting=custom_field]').forEach(setting => new CustomField(column, setting));
}

class CustomField {
    column: Column
    setting: HTMLElement
    select: HTMLSelectElement

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.select = setting.querySelector('.custom_field')!;
        this.bindEvents();
    }

    bindEvents() {
        const request = loadSingleRequestManager(this.select.dataset.type ?? '', this.select.dataset.post_type ?? '');
        const editingAvailable = this.column.getElement().querySelectorAll('[data-setting="edit"][data-indicator-toggle]').length > 0;

        // Ensure you won't get any duplicates on clone
        this.select.querySelectorAll('optgroup').forEach(el => {
            el.remove();
        });

        this.select.removeAttribute('data-select2-id');
        this.setting.querySelectorAll('.select2').forEach(el => {
            el.remove();
        });

        request.getOptions().then((data: any) => {
            (<any>$(this.select)).ac_select2({
                theme: 'acs2',
                width: '100%',
                tags: editingAvailable,
                dropdownCssClass: '-customfields',
                data: data
            });
        });
    }
}


class SingleCustomFieldRequestManager {

    metaType: string
    postType: string
    loading: boolean
    data: any
    events: Nanobus

    constructor(metaType: string, postType: string) {
        this.metaType = metaType;
        this.postType = postType;
        this.loading = false;
        this.data = null;
        this.events = new Nanobus();
    }

    retrieveOptions(): AxiosPromise {
        this.loading = true;
        let formData = new FormData();
        formData.set('action', 'ac_custom_field_options');
        formData.set('post_type', this.postType);
        formData.set('meta_type', this.metaType);
        formData.set('_ajax_nonce', AC._ajax_nonce);

        return axios.post(ajaxurl, formData)
    }

    getOptions() {
        return new Promise((resolve, reject) => {
            if (this.data) {
                resolve(this.data);
            } else if (this.loading) {
                this.events.on('loaded', () => {
                    resolve(this.data);
                })
            } else {
                this.retrieveOptions().then((response: AxiosResponse<any>) => {
                    if (!response.data.success) {
                        reject();
                    }

                    this.data = response.data.data.results;
                    this.events.emit('loaded');

                    resolve(this.data);
                });
            }
        });
    }

}

const loadSingleRequestManager = (metaType: string, postType: string) => {
    const key = `custom_field_${metaType}_${postType}`;

    if (typeof window.AC_Requests === 'undefined') {
        window.AC_Requests = {};
    }
    if (!window.AC_Requests.hasOwnProperty(key)) {
        window.AC_Requests[key] = new SingleCustomFieldRequestManager(metaType, postType);
    }

    return window.AC_Requests[key];
};