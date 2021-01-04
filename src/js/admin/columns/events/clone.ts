/*
* Column: bind clone events
*
* @since 2.0
*/

import {Column, COLUMN_EVENTS} from "../column";

export const initClone = (column: Column): void => {
    column.getElement().querySelectorAll<HTMLElement>('.clone-button').forEach(element => {
        element.addEventListener('click', e => {
            e.preventDefault();

            if (!column.isOriginal()) {
                column.events.emit(COLUMN_EVENTS.CLONE);
            }
        });
    });
}
