import {Column} from "../column";
import {AxiosResponse} from "axios";

import axios from "axios";

declare const ajaxurl: string;

export const initNumberFormatSetting = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('.ac-column-setting--number_format').forEach(setting => {
        new NumberFormat(column, setting);
    });
}

type numberFormatAjaxResponse = {
    success: boolean,
    data: string
}

class NumberFormat {

    column: Column
    setting: HTMLElement;

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.bindEvents();
    }

    bindEvents() {
        this.refreshPreview();
        this.setting.querySelectorAll('input').forEach(el => {
            el.addEventListener('change', () => this.refreshPreview());
        });
    }

    refreshPreview() {
        this.getExampleRequest().then((response: AxiosResponse<numberFormatAjaxResponse>) => {
            this.setting.querySelectorAll<HTMLElement>('[data-preview]').forEach(el => el.textContent = response.data.data);
        });
    }

    getValue() {
        let decimals = this.setting.querySelector<HTMLInputElement>('.ac-setting-input_number_decimals');
        let decimal_point = this.setting.querySelector<HTMLInputElement>('.ac-setting-input_number_decimal_point');
        let thousands_point = this.setting.querySelector<HTMLInputElement>('.ac-setting-input_number_thousands_separator');

        return {
            decimals: decimals ? decimals.value : '',
            decimal_point: decimal_point ? decimal_point.value : '',
            thousands_point: thousands_point ? thousands_point.value : '',
        }
    }

    getExampleRequest() {
        const value = this.getValue()

        let data = new FormData();
        data.set('action', 'ac_number_format');
        data.set('number', '7500');
        data.set('decimals', value.decimals);
        data.set('decimal_point', value.decimal_point);
        data.set('thousands_sep', value.thousands_point);

        return axios.post(ajaxurl, data, {})
    }
}