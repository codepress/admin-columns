import AcSection from "./modules/ac-section";
import {initPointers} from "./modules/ac-pointer";
import {initAcServices} from "./helpers/admin-columns";

const $ = require("jquery");

initAcServices();

initAcServices();

$(document).ready(() => {
    initPointers();

    document.querySelectorAll<HTMLElement>('.ac-section').forEach(el => {
        new AcSection(el);
    });

    $(document).on('select2:open', () => {
        document.querySelector<HTMLInputElement>('.select2-container--open .select2-search__field')?.focus();
    });
});