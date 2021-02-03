import {Column} from "../column";
// @ts-ignore
import $ from 'jquery';

export const initProSetting = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('.ac-column-setting--pro').forEach(setting => {
        setting.querySelectorAll<HTMLElement>('input').forEach(input => {
            input.addEventListener('click', () => $(setting).find('[data-ac-modal]').trigger('click'));
        });
    });
}