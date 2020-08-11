import {AddonDownload} from "./modules/addon-download";

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll<HTMLElement>('.ac-addon').forEach(element => {
        new AddonDownload(element, element.dataset.slug);
    });

});