import {LocalizedScriptACTable} from "../admincolumns";

const $ = require("jquery");

declare const ajaxurl: string
declare const AC: LocalizedScriptACTable

export default class ScreenOption {

    element: HTMLElement
    name: string

    constructor(element: HTMLElement, name: string) {
        this.name = name;
        this.element = element;
        this.init();
    }

    getInput(): HTMLInputElement {
        return this.element.querySelector<HTMLInputElement>('input');
    }

    init() {
        let input = this.getInput();
        if (input) {
            input.addEventListener('change', () => {
                this.persist();
            });
        }
    }

    persist() {
        return $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ac_admin_screen_options',
                option_name: this.name,
                option_value: this.getInput().checked ? 1 : 0,
                _ajax_nonce: AC._ajax_nonce
            }
        })
    }

}