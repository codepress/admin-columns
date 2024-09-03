import InfoScreenOption from "./admin/columns/screen-options";
import {showColumnName, showColumnType} from "./columns/store/screen-options";


document.addEventListener('DOMContentLoaded', () => {

    // Screen Options
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_id"] input').forEach(el =>
        new InfoScreenOption('show_column_id', el, showColumnName)
    );
    document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_column_type"] input').forEach(el =>
        new InfoScreenOption('show_column_type', el, showColumnType)
    );
    //document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_id"] input').forEach(el => new InfoScreenOption('show_list_screen_id', el, 'show-list-screen-id', document.querySelector('.ac-admin')!));
    //document.querySelectorAll<HTMLInputElement>('[data-ac-screen-option="show_list_screen_type"] input').forEach(el => new InfoScreenOption('show_list_screen_type', el, 'show-list-screen-type', document.querySelector('.ac-admin')!));
});