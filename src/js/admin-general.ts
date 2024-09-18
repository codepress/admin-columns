import AcSection from "./modules/ac-section";
import {initPointers} from "./modules/ac-pointer";
import {initAcServices} from "./helpers/admin-columns";
import {initUiToggleButtons} from "./ui-wrapper/functions";

initAcServices();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('.ac-section').forEach(el => {
        new AcSection(el);
    });

    initPointers();
    initUiToggleButtons();
});