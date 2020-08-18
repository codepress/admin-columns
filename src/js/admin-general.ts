import AcSection from "./modules/ac-section";
import {initPointers} from "./modules/ac-pointer";
import {initAdminColumnsGlobalBootstrap} from "./helpers/admin-columns";

const $ = require("jquery");

declare global {
    interface Window {
        ac_pointers: any
    }
}

initAdminColumnsGlobalBootstrap();
window.ac_pointers = initPointers;

$(document).ready(() => {
    initPointers();

    document.querySelectorAll<HTMLElement>('.ac-section').forEach(el => {
        new AcSection(el);
    });
});