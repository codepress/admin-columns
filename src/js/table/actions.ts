import {insertAfter} from "../helpers/elements";
import AcHtmlElement from "../helpers/html-element";

type ActionButtonData = {
    [key: number]: ActionButtonArray;
}

type ActionButtonArray = Array<ActionButton>;


export class ActionButton {

    private visible: boolean = true;

    constructor(private el: HTMLElement) {
    }

    toggle(show: boolean) {
        this.visible = show;

        return this;
    }

    isHidden() {
        return !this.visible;
    }

    getElement() {
        return new AcHtmlElement(this.el);
    }

    setTooltip(tooltip: string) {
        this.el.dataset.acTip = tooltip;

        return this;
    }

    static createWithMarkup(slug: string, label: string) {
        return new ActionButton(AcHtmlElement
            .create('a')
            .setAttribute('data-slug', slug)
            .addClasses('ac-table-button')
            .addHtml(label).getElement());
    }

}

class ActionButtonCollection {
    constructor(private data: ActionButtonData) {
    }

    add(button: ActionButton, priority: number) {
        if (!(priority in this.data)) {
            this.data[priority] = [];
        }
        this.data[priority].push(button);
    }

    getById(id: string) {
        this.getButtons().find(b => b.getElement().getElement().id === id);
    }

    getButtons(): ActionButtonArray {
        let result: ActionButtonArray = [];

        Object.keys(this.data).forEach((priority) => {
            this.data[parseInt(priority)].forEach(button => {
                result.push(button);
            })
        })

        return result;
    }

    getReversedButtons(): ActionButtonArray {
        return this.getButtons().reverse();
    }


}

export default class Actions {

    private buttons: ActionButtonCollection

    constructor(private container: HTMLElement) {
        this.buttons = new ActionButtonCollection({});
        this.init();
    }

    getButton(id: string) {
        return this.buttons.getById(id);
    }

    addButton(button: ActionButton, priority: number) {
        this.buttons.add(button, priority);
    }

    init() {
        this.container.addEventListener('update', () => {
            this.refresh();
        });

        document.querySelectorAll<HTMLElement>('.tablenav.top .actions').forEach(el => {
            insertAfter(this.container, el);
            this.container.classList.add('-init');
        });

        this.container.querySelectorAll<HTMLElement>('.ac-table-actions-buttons .ac-table-button').forEach(button => {
            let actionButton = new ActionButton(button);

            if (!button.offsetParent) {
                actionButton.toggle(false);
                actionButton.getElement().getElement().remove();
            }

            this.addButton(actionButton, parseInt(button.dataset?.priority ?? '10'))
        });
    }

    getElement() {
        return this.container;
    }

    toggle( show: boolean = true ){
        if(  show ){
            this.getElement().classList.remove( '-hidden' )
        } else {
            this.getElement().classList.add( '-hidden' )
        }

    }


    refresh() {
        this.buttons.getButtons().forEach(button => {
            if (button.isHidden()) {
                button.getElement().getElement().remove();
            } else {
                this.container.querySelector('.ac-table-actions-buttons')?.append(button.getElement().getElement());
            }
        });

        this.toggle( this.buttons.getButtons().length !== 0 )

    }

}