import {EventConstants} from "./constants";
import InfoScreenOption from "./admin/columns/screen-options";
import {initAcTooltips} from "./plugin/tooltip";
import {initPointers} from "./modules/ac-pointer";
import {initUninitializedListScreens} from "./admin/columns/listscreen-initialize";
import {Column} from "./admin/columns/column";


document.addEventListener('DOMContentLoaded', () => {
    if (AC.hasOwnProperty('uninitialized_list_screens')) {
        initUninitializedListScreens(AC.uninitialized_list_screens);
    }

    // Screen Options
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_id"] input').forEach(el => new InfoScreenOption('show_column_id', el, 'show-column-id', document.querySelector('.ac-boxes')!));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_type"] input').forEach(el => new InfoScreenOption('show_column_type', el, 'show-column-type', document.querySelector('.ac-boxes')!));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_id"] input').forEach(el => new InfoScreenOption('show_list_screen_id', el, 'show-list-screen-id', document.querySelector('.ac-admin')!));
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_type"] input').forEach(el => new InfoScreenOption('show_list_screen_type', el, 'show-list-screen-type', document.querySelector('.ac-admin')!));
});


AcServices.addListener(EventConstants.SETTINGS.COLUMN.INIT, (column: Column) => {
    initAcTooltips();
    initPointers(column.getElement().querySelectorAll('.ac-pointer'));
});