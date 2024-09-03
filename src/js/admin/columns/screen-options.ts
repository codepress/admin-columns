import axios from "axios";
import {mapDataToFormData} from "../../helpers/global";
import {Writable} from "svelte/store";


declare const ajaxurl: string;

export default class InfoScreenOption {


    constructor(private name: string, private input: HTMLInputElement, private store:Writable<boolean>) {
        this.initEvents();
    }

    initEvents() {
        this.store.set(this.input.checked);
        this.input.addEventListener('change', () => {
            this.store.set(this.input.checked);
            this.persist();
        });
    }

    persist() {
        axios.post(ajaxurl, mapDataToFormData({
            action: 'ac-admin-screen-options',
            _ajax_nonce: ac_admin_columns.nonce,
            option_name: this.name,
            option_value: this.input.checked ? 1 : 0
        }))
    }
}