import {Column} from "../column";
import SelectOptions from "./component/SelectOptions.svelte";


export const initSelectOptions = (column: Column) => {
    column.getElement().querySelectorAll<HTMLElement>('[data-setting="select_options"]').forEach(setting =>
        new SelectOption(column, setting, setting.querySelector('input[name="select_options"]')!)
    );
}

class SelectOption {

    private component: SelectOptions | null;

    constructor(private column: Column, private setting: HTMLElement, private input: HTMLInputElement) {
        this.column = column;
        this.setting = setting;

        input.type = 'hidden';

        const componentContainer = setting.querySelector('[data-component="ac-select-options"]');
        this.component = componentContainer
            ? new SelectOptions({
                target: componentContainer,
                props: {
                    options: this.input.value ? JSON.parse(this.input.value) : [],
                    input: this.input
                }
            })
            : null;
    }

}