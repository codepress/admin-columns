import {Column} from "../column";
// @ts-ignore
import $ from 'jquery';

export const initTypeSelector = (column: Column) => {
    column.getElement().querySelectorAll<HTMLSelectElement>('select.ac-setting-input_type').forEach(select => {
        $(select).on('change', () => column.switchToType(select.value));
    });
}