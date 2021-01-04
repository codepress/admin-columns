/*
 * Column: bind toggle events
 *
 * For performance we bind all other events after the click event.
 *
 * @since 2.0
 */
import {Column} from "../column";

export const initToggle = (column: Column) => {
    column.getElement().querySelectorAll('[data-toggle="column"]').forEach((el: HTMLElement) => {
        el.addEventListener('click', e => column.toggle());
        el.style.cursor = 'pointer';
    });
}