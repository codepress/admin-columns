import axios from "axios";
import {mapDataToFormData} from "../helpers/global";
import {nonceParam} from "../helpers/nonce";
import {NonceData} from "../types/requests";
import {Writable} from "svelte/store";


declare const ajaxurl: string;

export default class InfoScreenOption {

    constructor(private name: string, private input: HTMLInputElement, private store: Writable<boolean>, private nonce: NonceData) {
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
            ...nonceParam(this.nonce),
            option_name: this.name,
            option_value: this.input.checked ? 1 : 0
        }))
    }
}