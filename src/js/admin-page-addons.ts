import AddonsPage from "./addons/AddonsPage.svelte";
import AddonsPageFree from "./addons/AddonsPageFree.svelte";
import {getAddonsConfig} from "./addons/global";

document.addEventListener("DOMContentLoaded", function () {

    const config = getAddonsConfig();
    const Component = config.pro_installed ? AddonsPage : AddonsPageFree;

    document.querySelectorAll('#cpac').forEach(el => {
        new Component({
            target: el,
            props: config.pro_installed ? { pro: true } : {}
        })
    });

});