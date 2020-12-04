import {Column} from "../column";

export const initIndicator = (column: Column) => {
    if (column.isDisabled()) return;

    column.getElement().querySelectorAll('.ac-column-header [data-indicator-toggle]').forEach((toggleElement: HTMLElement) => {
        let relatedSettings = column.getElement().querySelectorAll<HTMLInputElement>(`.ac-column-setting[data-setting='${toggleElement.dataset.setting}'] .col-input .ac-setting-input:first-child input[type=radio]`);

        toggleElement.addEventListener('click', () => {
            switchTo(!toggleElement.classList.contains('on'), relatedSettings)
        });

        relatedSettings.forEach(element => {
            element.addEventListener('change', () => {
                element.value === 'off'
                    ? toggleElement.classList.remove('on')
                    : toggleElement.classList.add('on')
            });
        })

    });
}

const switchTo = (checked: Boolean, elements: NodeListOf<HTMLInputElement>) => {
    let checkvalue: string = checked ? 'on' : 'off';
    elements.forEach(el => {
        if (el.value === checkvalue) {
            el.checked = true;
            el.dispatchEvent(new Event('change'));
            el.dispatchEvent(new Event('click'));
        }
    });
}
