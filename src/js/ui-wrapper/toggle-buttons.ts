import AcToggleButtons from "ACUi/acui-toggle-buttons/AcToggleButtons.svelte";
import AcHtmlElement from "../helpers/html-element";

type ToggleButtonsOptions = Array<{ value: string, label: string }>;

export default class AcuiToggleButtonsWrapper {

    private options: ToggleButtonsOptions;

    constructor(private input: HTMLInputElement) {

        this.parseOptions();
        this.wrap();
    }

    parseOptions() {
        const dataOptions = this.input.dataset.options ? JSON.parse(this.input.dataset.options) : {};
        let options: Array<{ value: string, label: string }> = [];

        Object.keys(JSON.parse(this.input.dataset.options!)).forEach(key => {
            options.push({value: key, label: dataOptions[key]})
        });

        this.options = options;
    }

    wrap() {
        const wrapper = AcHtmlElement.create('div').addClass('acui-plugin-toggle-buttons');
        const target = AcHtmlElement.create('div');

        this.input.readOnly = true;
        this.input.type = 'hidden';
        this.input.parentElement!.insertBefore(wrapper.getElement(), this.input);
        wrapper.append(this.input).append(target.getElement());

        new AcToggleButtons({
            target: target.getElement(),
            props: {
                onChange: (e: string) => {
                    this.input.value = e;
                    this.input.dispatchEvent(new Event('change'));
                    this.input.dispatchEvent(new Event('input'));
                },
                value: this.input.value,
                options: this.options
            }
        });


    }

}