import {Column} from "../column";
// @ts-ignore
import $ from 'jquery';
import AcHtmlElement from "../../../helpers/html-element";

export const initMultiSelectFields = (column: Column) => {
    column.getElement().querySelectorAll<HTMLSelectElement>('select[multiple]').forEach(select => {
        new MultiSelect(column, select);
    })
}

class MultiSelect {
    column: Column
    select: HTMLSelectElement

    constructor(column: Column, select: HTMLSelectElement) {
        this.column = column;
        this.select = select;
        this.bindEvents();
    }

    getSelectedOptions() {
        const selected = this.select.querySelectorAll<HTMLOptionElement>('option:checked');

        return Array.from(selected).map(el => el.value);
    }

    bindEvents() {
        // First remove all cloned Select2 elements
        this.select.removeAttribute('data-select2-id');
        this.select.parentElement?.querySelectorAll('.select2').forEach(el => {
            el.remove();
        });

        let fallBack = AcHtmlElement.create('input')
            .setAttributes({
                'name': this.select.getAttribute('name') ?? '',
                'type': 'hidden'
            });

        if (this.getSelectedOptions().length === 0) {
            fallBack.insertSelfBefore(this.select);
        }

        (<any>$(this.select)).ac_select2({
            theme: 'acs2',
            width: '100%',
            closeOnSelect: false,
            escapeMarkup: function (text: string) {
                return text;
            },
        }).on('select2:selecting', () => {
            fallBack.getElement().remove();
        }).on('select2:unselect', () => {
            if (this.getSelectedOptions().length === 0) {
                fallBack.insertSelfBefore(this.select);
            }
        });
    }
}
