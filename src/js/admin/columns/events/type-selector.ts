import {Column} from "../column";

export const initTypeSelector = (column: Column) => {
    column.getElement().querySelectorAll<HTMLSelectElement>('select.ac-setting-input_type').forEach(select => {
        select.addEventListener('change', () => column.switchToType(select.value));
    });
}