import {Column} from "../column";

export const initColumnRefresh = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('[data-refresh="column"]').forEach(element => {
        element.addEventListener('change', () => {
            // Allow other settings to do their thing first so all changes are refreshed correctly
            setTimeout(() => column.refresh(), 50);
        });
    });
}