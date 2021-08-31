import AcSection from "./modules/ac-section";
import {initPointers} from "./modules/ac-pointer";

const $ = require("jquery");

declare global {
    interface Window {
        ac_pointers: any
    }
}

$(document).ready(() => {
    initPointers();

    document.querySelectorAll<HTMLElement>('.ac-section').forEach(el => {
        new AcSection(el);
    });

    $(document).on('select2:open', () => {
        let searchBox: HTMLInputElement = document.querySelector('.select2-container--open .select2-search__field');
        if( searchBox ){
            searchBox.focus();
        }
    });
});