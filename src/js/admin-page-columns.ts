import {initAcServices} from "./helpers/admin-columns";
import ColumnsPage from "./columns/components/ColumnsPage.svelte";
import {currentListId, currentListKey} from "./columns/store/current-list-screen";
import {getColumnSettingsConfig} from "./columns/utils/global";
import {columnTypesStore} from "./columns/store/column-types";
import {favoriteListKeysStore} from "./columns/store/favorite-listkeys";
import {debugMode} from "./columns/store/debug";
import {showColumnInfo} from "./columns/store/screen-options";
import {initUninitializedListScreens} from "./columns/utils/listscreen-initialize";
import InfoScreenOption from "./modules/screen-options";
import ColumnPageBridge from "./columns/utils/page-bridge";

const AcServices = initAcServices();
const localConfig = getColumnSettingsConfig();

require('./columns/init/setting-types.ts');

currentListKey.subscribe((d) => {
    const url = new URL(window.location.href);

    url.searchParams.set('list_screen', d);

    window.history.replaceState(null, '', url);
})

currentListId.subscribe((d) => {
    const url = new URL(window.location.href);

    url.searchParams.set('layout_id', d);

    window.history.replaceState(null, '', url);
})


document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('admin-columns__columns')


    const pageBridge = new ColumnPageBridge();


    AcServices.registerService('ColumnPage', pageBridge);

    currentListId.set(localConfig.list_id)
    currentListKey.set(localConfig.list_key);
    columnTypesStore.set([]);
    debugMode.set(false);
    favoriteListKeysStore.set(localConfig.menu_items_favorites);

    const cpacElement = document.querySelector('#cpac');

    if (cpacElement) {
        new ColumnsPage({
            target: cpacElement,
            props: {
                menu: localConfig.menu_items,
                openedGroups: localConfig.menu_groups_opened
            }
        });
    }

    if (localConfig.uninitialized_list_screens) {
        initUninitializedListScreens(localConfig.uninitialized_list_screens, localConfig.list_key);
    }

    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_info"] input').forEach(el =>
        new InfoScreenOption('show_column_info', el, showColumnInfo, ac_admin_columns.nonce)
    );

});