import {Column} from "../column";

export const initIndicator = (column: Column) => {
    if (column.isDisabled()) return;

    column.getElement().querySelectorAll('[data-indicator-toggle]').forEach((indicatorElement: HTMLElement) => {
        let settingName = indicatorElement.dataset.setting ?? '';

        let radioInputs = column.getElement().querySelectorAll<HTMLInputElement>(`.ac-column-setting[data-setting='${indicatorElement.dataset.setting}'] .col-input .ac-setting-input:first-child input[type=radio]`);
        if (radioInputs.length) {
            initRadioRelations(column, indicatorElement, settingName);
        }

        let toggleInput = column.getElement().querySelector<HTMLInputElement>(`.ac-column-setting[data-setting='${indicatorElement.dataset.setting}'] .col-input .ac-setting-input:first-child .ac-toggle-v2`);
        if (toggleInput) {
            initToggleRelation(column, indicatorElement, settingName, toggleInput);
        }
    });
}


const initToggleRelation = (column: Column, indicatorElement: HTMLElement, setting: string, toggleSetting: HTMLElement) => {
    let checkBox: HTMLInputElement = toggleSetting.querySelector('input[type=checkbox]') ?? document.createElement('input');

    indicatorElement.addEventListener('click', () => {
        checkBox.checked = !checkBox.checked;
        checkBox.dispatchEvent(new Event('input'));
    });

    checkBox.addEventListener('input', () => checkBox.checked ? indicatorElement.classList.add('on') : indicatorElement.classList.remove('on'));
}

const initRadioRelations = (column: Column, indicatorElement: HTMLElement, setting: string) => {
    let relatedSettings = column.getElement().querySelectorAll<HTMLInputElement>(`.ac-column-setting[data-setting='${setting}'] .col-input .ac-setting-input:first-child input[type=radio]`);

    indicatorElement.addEventListener('click', () => {
        switchTo(!indicatorElement.classList.contains('on'), relatedSettings)
    });

    relatedSettings.forEach(element => {
        element.addEventListener('change', () => {
            element.value === 'off'
                ? indicatorElement.classList.remove('on')
                : indicatorElement.classList.add('on')
        });
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
