import excludeGroupsMather from "../../../select2/excludegroup.matcher";
import {Column} from "../column";
// @ts-ignore
import $ from 'jquery';

export const initColumnTypeSelectorSetting = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('[data-setting="type"]').forEach(setting => {
        new TypeSelector(column, setting);
    })
}

class TypeSelector {
    column: Column
    setting: HTMLElement

    constructor(column: Column, setting: HTMLElement) {
        this.column = column;
        this.setting = setting;
        this.bindEvents();
    }

    bindEvents() {
        const select = this.setting.querySelector('.ac-setting-input_type');

        if (select) {
            select.removeAttribute('data-select2-id');

            this.setting.querySelectorAll('.select2').forEach(el => {
                el.remove();
            });

            $(select).ac_select2({
                theme: 'acs2',
                width: '100%',
                dropdownCssClass: '-type-selector',
                escapeMarkup: function (text: string) {
                    return text;
                },
                templateResult: function (result: any) {
                    let text = result.text;

                    if (result.hasOwnProperty('id') && result.id.includes('placeholder-')) {
                        text += `<span style="background-color:#FE3D6C; color:#fff; font-size: 10px; margin-top: -1px; padding: 1px 5px; border-radius: 2px; text-transform: uppercase;float: right; margin-right 10px;">PRO</span>`;
                    }

                    return text;
                },
                matcher: excludeGroupsMather
            });
        }
    }
}
