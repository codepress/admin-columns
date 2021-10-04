// @ts-ignore
import ValueModalComponent from "../svelte/ValueModal.svelte";

export type ValueModal = {
    element: HTMLLinkElement,
    columnName: string,
    objectId: number
}

export default class ValueModals {

    links: Array<ValueModal>
    component: any;

    constructor(links: Array<ValueModal>) {
        this.links = links;
        this.component = null;
        this.initEventHandlers();
    }

    private initEventHandlers() {
        this.links.forEach(item => {
            item.element.addEventListener('click', (e) => {
                e.preventDefault();
                this.displayModal(item.objectId)
            });
        });
    }

    private destroyComponent() {
        if (this.component !== null) {
            this.component.$destroy();
        }
    }

    private displayModal(id: number) {
        let element = document.createElement('div');
        document.body.appendChild(element);

        this.component = new ValueModalComponent({
            target: element,
            props: {
                items: this.links,
                objectId: id,
                destroyHandler: () => this.destroyComponent()
            }
        });

    }

}