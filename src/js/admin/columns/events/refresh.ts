import {Column} from "../column";

export const initColumnRefresh = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('[data-refresh="column"]').forEach(element => {
        element.addEventListener('change', () => {
            column.refresh();
        });
    });
}