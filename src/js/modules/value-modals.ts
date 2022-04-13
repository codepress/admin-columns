// @ts-ignore
import ValueModalComponent from "../components/ValueModal.svelte";
import {ValueModalItemCollection} from "../types/admin-columns";


export default class ValueModals {

    component: any;

    constructor(private links: ValueModalItemCollection) {
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