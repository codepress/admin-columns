import {AddonDownload} from "./modules/addon-download";
import {AddonActivator} from "./modules/addon-activator";

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll<HTMLElement>('.ac-addon').forEach(element => {
        new AddonDownload(element, element.dataset.slug);
        new AddonActivator(element, element.dataset.slug);
    });

});