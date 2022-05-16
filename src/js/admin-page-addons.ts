import {AddonDownload} from "./modules/addon-download";

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll<HTMLElement>('.ac-addon').forEach(element => {
        if (!!element.dataset.slug) {
            new AddonDownload(element, element.dataset.slug);
        }
    });

});