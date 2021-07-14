import axios from "axios";
import {mapDataToFormData} from "./helpers/global";

declare const ajaxurl: string; 

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLInputElement>('.ac-settings-box input[data-ajax-setting]').forEach(el => {
        new GeneralAdminSetting(el, el.dataset.ajaxSetting);
    });
})

class GeneralAdminSetting {

    element: HTMLInputElement
    name: string

    constructor(element: HTMLInputElement, name: string) {
        this.element = element;
        this.name = name;

        this.init();
    }

    init() {
        this.element.addEventListener('change', () => {
            this.persist();
        });
    }

    persist() {
        return axios.post(ajaxurl, mapDataToFormData({
            action: 'ac_admin_general_options',
            //_ajax_nonce: AC._ajax_nonce,
            option_name: this.name,
            option_value: this.element.checked ? '1' : '0'
        }));
    }


}