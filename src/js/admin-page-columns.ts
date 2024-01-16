import {initAcServices} from "./helpers/admin-columns";
import {registerSettingType} from "./columns/helper";
import ColumnsPage from "./columns/components/ColumnsPage.svelte";
import {currentListId, currentListKey, initialListId} from "./columns/store/current-list-screen";
import {getColumnSettingsConfig} from "./columns/utils/global";
import ListScreenSections from "./columns/store/list-screen-sections";
import {listScreenDataStore} from "./columns/store/list-screen-data";
import {columnTypeSorter, columnTypesStore} from "./columns/store/column-types";
import {listScreenIsReadOnly} from "./columns/store/read_only";

const AcServices = initAcServices();
const config = getColumnSettingsConfig();

// TODO clean up legacy columns and check what is necessary
require('./_legacy-columns.ts');
require('./columns/init/setting-types.ts');

initialListId.set(config.list_id);

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


    // TODO make something more affording
    const ConfigService = {
        stores: {
            currentListId,
            currentListKey,
            listScreenDataStore,
            listScreenIsReadOnly
        },
        registerSettingType,
        ListScreenSections,
    }

    AcServices.registerService('ColumnPage', ConfigService);

    currentListId.set(config.list_id)
    currentListKey.set(config.list_key);
    columnTypesStore.set(config.column_types.sort(columnTypeSorter));

    const target = document.createElement('div');

    new ColumnsPage({
        target: target,
        props: {
            menu: config.menu_items,
        }
    });

    document.querySelector('#cpac')?.prepend(target);
});