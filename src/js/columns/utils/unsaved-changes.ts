import {listScreenDataHasChanges, listScreenIsReadOnly} from "../store";
import {get} from "svelte/store";
import AcConfirmation from "../../plugin/ac-confirmation";
import {getColumnSettingsTranslation} from "./global";
import {initAcServices} from "../../helpers/admin-columns";
import ColumnPageBridge from "./page-bridge";

export const checkChangesWarning = async () => {
    const services = initAcServices();
    return new Promise((resolve, reject) => {
        const i18n = getColumnSettingsTranslation();
        const bridge = services.getService<ColumnPageBridge>('ColumnPage');

        const hasChanges = get( bridge?.getStore( 'listScreenDataHasChanges' )! );
        const isReadOnly = get( bridge?.getStore( 'listScreenIsReadOnly' )! );

        if (!hasChanges || isReadOnly) {
            resolve(true);
            return;
        }

        new AcConfirmation({
            message: i18n.notices.unsaved_changes_leave,
            confirm: () => resolve(true),
            oncancel: () => resolve(false),
        }).create();
    });
}