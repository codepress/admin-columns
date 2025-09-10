import SettingsPage from "./admin-settings/SettingsPage.svelte";
import RestoreSettingsSection from "./admin-settings/RestoreSettingsSection.svelte";
import SettingsPageBridge from "./admin-settings/utils/page-bridge";
import {initAcServices} from "./helpers/admin-columns";

const AcServices = initAcServices();

let restoreSettingsElement = document.createElement('div');
new RestoreSettingsSection({
    target: restoreSettingsElement,
})

document.addEventListener("DOMContentLoaded", function () {
    const Bridge = new SettingsPageBridge();
    AcServices.registerService('SettingsPage', Bridge);

    let restoreSettingsElement = document.createElement('div');
    new RestoreSettingsSection({
        target: restoreSettingsElement,
    })

    Bridge.getSections().registerSection('after_general', restoreSettingsElement.firstElementChild as HTMLElement);

    document.querySelectorAll('#cpac').forEach(el => {
        new SettingsPage({
            target: el,
        })
    });

});