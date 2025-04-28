import {initAcServices} from "./helpers/admin-columns";
import ColumnsPage from "./columns/components/ColumnsPage.svelte";
import {
    currentListId,
    currentListKey,
    debugMode,
    columnTypesStore,
    favoriteListKeysStore,
    showColumnInfo,
    hasUsagePermissions,
    listScreenDataStore, listScreenDataHasChanges, initialListScreenData
} from "./columns/store";
import {getColumnSettingsConfig} from "./columns/utils/global";
import {initListScreenHeadings, initUninitializedListScreens} from "./columns/utils/listscreen-initialize";
import InfoScreenOption from "./modules/screen-options";
import ColumnPageBridge from "./columns/utils/page-bridge";
import { get } from "svelte/store";

const AcServices = initAcServices();
const localConfig = getColumnSettingsConfig();

require('./columns/init/setting-types.ts');

const debounce = <T extends (...args: any[]) => void>(
    fn: T,
    delay: number
): (...args: Parameters<T>) => void => {
    let timeoutId: ReturnType<typeof setTimeout>;

    return (...args: Parameters<T>): void => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
}

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


// Debounced vergelijking
const checkForChanges = debounce(() => {
    const orig = get(initialListScreenData);
    const current = get(listScreenDataStore);

    console.log( 'change', JSON.stringify(orig) !== JSON.stringify(current));
    console.log( 'orig', orig);
    listScreenDataHasChanges.set(JSON.stringify(orig) !== JSON.stringify(current));
}, 300);

listScreenDataStore.subscribe( d => {
    checkForChanges();
})


document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('admin-columns__columns')

    currentListId.set(localConfig.list_id)
    currentListKey.set(localConfig.list_key);
    columnTypesStore.set([]);
    hasUsagePermissions.set( true )
    debugMode.set(false);
    favoriteListKeysStore.set(localConfig.menu_items_favorites);

    const pageBridge = new ColumnPageBridge();

    AcServices.registerService('ColumnPage', pageBridge);

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

    if (localConfig.uninitialized_list_screens !== null) {
        initUninitializedListScreens(localConfig.uninitialized_list_screens, localConfig.list_key);
    }
    initListScreenHeadings();

    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_info"] input').forEach(el =>
        new InfoScreenOption('show_column_info', el, showColumnInfo, ac_admin_columns.nonce)
    );

});