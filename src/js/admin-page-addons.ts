import AddonsPage from "./addons/AddonsPage.svelte";

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('#cpac').forEach(el => {
        new AddonsPage({
            target: el,
            props: {
                pro: AC_ADDONS.pro_installed
            }
        })
    });

});