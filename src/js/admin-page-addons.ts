import {AddonDownload} from "./modules/addon-download";

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll<HTMLElement>('.ac-addon .acu-pt-3').forEach(element => {
        if (!!element.dataset.slug) {
            new AddonDownload(element, element.dataset.slug);
        }
    });

});