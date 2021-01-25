import {Column} from "../column";
import excludeGroupsMather from "../../../select2/excludegroup.matcher";
// @ts-ignore
import $ from 'jquery';

export const initMultiSelectFields = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('select[multiple=multiple]').forEach(setting => {
        new MultiSelect(column, setting);
    })
}

class MultiSelect {
    column: Column
    setting: HTMLElement

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.bindEvents();
    }

    bindEvents() {
        $(this.setting).ac_select2({
            theme: 'acs2',
            width: '100%',
            escapeMarkup: function (text: string) {
                return text;
            },
        });
    }
}
