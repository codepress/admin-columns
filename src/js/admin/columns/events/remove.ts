import {Column} from "../column";

export const initRemoveColumn = (column: Column ): void => {
    column.getElement().querySelectorAll('[data-remove-column]').forEach( ( element: HTMLElement ) =>{
        element.addEventListener( 'click', e =>{
            e.preventDefault();
            column.remove();
        })
    } );
}