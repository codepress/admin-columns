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
});