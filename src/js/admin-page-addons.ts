import AddonsPage from "./addons/AddonsPage.svelte";
import {getAddonsConfig} from "./addons/global";

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('#cpac').forEach(el => {
        new AddonsPage({
            target: el,
            props: {
                pro: getAddonsConfig().pro_installed
            }
        })
    });

});