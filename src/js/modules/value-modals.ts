// @ts-ignore
import ValueModalComponent from "../components/ValueModal.svelte";
import {ValueModalItemCollection} from "../types/admin-columns";



export default class ValueModals {

    component: any;
    container: HTMLElement | null;

    constructor(private links: ValueModalItemCollection) {
        this.component = null;
        this.container = null;
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
            this.component = null;
        }
        if (this.container !== null) {
            this.container.remove();
            this.container = null;
        }
    }

    private displayModal(id: number) {
        this.destroyComponent();

        let element = document.createElement('div');
        document.body.appendChild(element);
        this.container = element;

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