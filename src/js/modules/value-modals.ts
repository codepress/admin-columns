// @ts-ignore
import ValueModalComponent from "../components/ValueModal.svelte";

export type ValueModal = {
    element: HTMLElement,
    title: string | null,
    editLink: string,
    downloadLink: string,
    columnName: string,
    objectId: number
}

export type ValueModalCollection = Array<ValueModal>

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