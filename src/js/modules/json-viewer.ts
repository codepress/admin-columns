// @ts-ignore
import JsonValueComponent from "../components/JsonValue.svelte";

export default class JsonViewer {

    component: any;
    value: string;
    level: number;

    constructor(private element: HTMLElement) {
        this.value = element.dataset.json ?? '';
        this.level = element.dataset.level ? parseInt(element.dataset.level) : 0;
        this.initEventHandlers();
    }

    private initEventHandlers() {
        let element = document.createElement('div');
        this.element.innerText = '';
        this.element.append(element);

        this.component = new JsonValueComponent({
            target: element,
            props: {
                value: JSON.parse(this.value),
                level: this.level
            }
        });
    }

}