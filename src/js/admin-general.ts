import AcSection from "./modules/ac-section";
import {initPointers} from "./modules/ac-pointer";
import {initAcServices} from "./helpers/admin-columns";
import {initUiToggleButtons} from "./ui-wrapper/functions";
import {persistGeneralSetting} from "./ajax/settings";
import AjaxLoader from "./plugin/ajax-loader";

initAcServices();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('.ac-section').forEach(el => {
        new AcSection(el);
    });

    jQuery(document).on('select2:open', () => {
        document.querySelector<HTMLInputElement>('.select2-container--open .select2-search__field')?.focus();
    });

    initPointers();
    initUiToggleButtons();
});