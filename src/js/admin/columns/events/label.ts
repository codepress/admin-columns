import {Column} from "../column";

export const initLabel = (column: Column) => {
    column.getElement().querySelectorAll<HTMLSelectElement>('select[data-label="update"]').forEach((select) => {
        select.addEventListener('change', () => {
            let labelSetting = column.getElement().querySelector<HTMLInputElement>('input.ac-setting-input_label');
            let option = select.querySelector('option:selected');

            if (labelSetting && option) {
                labelSetting.value = option.innerHTML;
                labelSetting.dispatchEvent(new Event('change'));
            }
        });
    });

    setTimeout(() => {
        let label = column.getElement().querySelector<HTMLElement>('.column_label .toggle');

        if (label && label.offsetWidth < 10) {
            label.innerText = column.getType();
        }
    }, 50)
}

export const initLabelSetting = (column: Column) => {
    let labelInput = column.getElement().querySelector<HTMLInputElement>('.ac-column-setting--label input');

    if (!labelInput) {
        return;
    }

    labelInput.addEventListener('change', () => changeLabel(labelInput, column));
    labelInput.addEventListener('keyup', () => changeLabel(labelInput, column));

}

const changeLabel = (labelInput: HTMLInputElement, column: Column) => {
    column.getElement().querySelector('td.column_label .inner > a.toggle').innerHTML = labelInput.value;
}