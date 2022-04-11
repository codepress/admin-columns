import {Column} from "../column";
// @ts-ignore
import $ from 'jquery';

export const initMultiSelectFields = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('select[multiple]').forEach(select => {
        new MultiSelect(column, select);
    })
}

class MultiSelect {
    column: Column
    select: HTMLElement

    constructor(column: Column, select: HTMLElement) {
        this.column = column;
        this.select = select;
        this.bindEvents();
    }

    bindEvents() {
        // First remove all cloned Select2 elements
        this.select.removeAttribute('data-select2-id');
        this.select.parentElement?.querySelectorAll('.select2').forEach(el => {
            el.remove();
        });

        (<any>$(this.select)).ac_select2({
            theme: 'acs2',
            width: '100%',
            escapeMarkup: function (text: string) {
                return text;
            },
        });
    }
}
