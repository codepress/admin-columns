import axios from "axios";
import {mapDataToFormData} from "./helpers/global";
import {LocalizedAcGeneralSettings} from "./types/admin-columns";
import AjaxLoader from "./plugin/ajax-loader";

declare const ajaxurl: string;
declare const AC: LocalizedAcGeneralSettings

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLInputElement>('.ac-settings-box input[data-ajax-setting]').forEach(el => {
        new GeneralAdminSetting(el, el.dataset.ajaxSetting);
    });
})

class GeneralAdminSetting {

    element: HTMLInputElement
    name: string
    loader: AjaxLoader

    constructor(element: HTMLInputElement, name: string) {
        this.element = element;
        this.name = name;
        this.loader = null;

        this.init();
    }

    init() {
        this.element.addEventListener('change', () => {
            this.initNewLoader();
            this.element.closest('.ac-toggle-v2').append(this.loader.getElement());
            this.loader.setLoading(true);
            this.persist().then(() => {
                this.loader.finish();
            });
        });
    }

    private initNewLoader() {
        if (this.loader !== null) {
            this.loader.getElement().remove();
        }

        this.loader = new AjaxLoader();
    }

    persist() {
        return axios.post(ajaxurl, mapDataToFormData({
            action: 'ac_admin_general_options',
            _ajax_nonce: AC._ajax_nonce,
            option_name: this.name,
            option_value: this.element.checked ? '1' : '0'
        }));
    }


}