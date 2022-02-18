import {insertAfter} from "../helpers/elements";

var nanobus = require('nanobus')

export default class Actions {

    private container: HTMLElement
    events: any

    constructor(element: HTMLElement) {
        this.container = element;
        this.events = nanobus();
        this.init();
    }

    init() {
        this.container.addEventListener('update', () => {
            this.refresh();
        });

        let reference = document.querySelectorAll<HTMLElement>('.tablenav.top .actions');

        if (reference && reference.length) {
            insertAfter(this.container, reference[reference.length - 1])
            this.container.classList.add('-init');
            this.container.dispatchEvent(new CustomEvent('update'));
        }
    }

    getElement(){
        return this.container;
    }

    refresh() {
        this.container.querySelectorAll('.ac-table-actions-buttons > a').forEach((element) => {
            element.classList.remove('last');
        });

        let buttons = [].slice.call(this.container.querySelectorAll('.ac-table-actions-buttons > a'), 0);
        buttons.reverse();

        for (var i = 0; i < buttons.length; i++) {
            if (buttons[i].offsetParent) {
                buttons[i].classList.add('last');
                break;
            }
        }
    }

}