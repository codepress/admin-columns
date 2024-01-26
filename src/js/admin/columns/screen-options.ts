import axios from "axios";
import {mapDataToFormData} from "../../helpers/global";
import {LocalizedAcColumnSettings} from "../../types/admin-columns";

declare const ajaxurl: string;
declare const AC: LocalizedAcColumnSettings;

export default class InfoScreenOption {
    input: HTMLInputElement
    toggleClass: string
    container: HTMLElement
    name: string

    constructor(name: string, input: HTMLInputElement, toggleClass: string, container: HTMLElement) {
        this.name = name;
        this.input = input;
        this.toggleClass = toggleClass;
        this.container = container;
        this.initEvents();
    }

    initEvents() {
        this.input.addEventListener('change', () => {
            this.input.checked
                ? this.container.classList.add(this.toggleClass)
                : this.container.classList.remove(this.toggleClass)

            this.persist();
        });
    }

    persist() {
        axios.post(ajaxurl, mapDataToFormData({
            action: 'ac-admin-screen-options',
            _ajax_nonce: AC._ajax_nonce,
            option_name: this.name,
            option_value: this.input.checked ? 1 : 0
        }))
    }
}