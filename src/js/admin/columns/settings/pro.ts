import {Column} from "../column";

export const initProSetting = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('.ac-column-setting--pro input').forEach(setting => {
        setting.querySelectorAll<HTMLElement>('input').forEach(input => {
            input.addEventListener('click', () => {
                let modalTriggers = this.setting.querySelectorAll('[data-ac-open-modal]');

                if (modalTriggers) {
                    modalTriggers[0].dispatchEvent(new Event('click'));
                }
            })
        });
    });
}