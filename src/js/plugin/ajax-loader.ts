import AcHtmlElement from "../helpers/html-element";
import {fadeOut} from "../helpers/animations";
import Nanobus from "nanobus";

export default class AjaxLoader {

    private element: HTMLElement
    private events: Nanobus;

    constructor() {
        this.element = createMarkup();
        this.events = new Nanobus();
    }

    getElement(): HTMLElement {
        return this.element;
    }

    setActive(active: boolean = true) {
        active
            ? this.element.classList.add('-active')
            : this.element.classList.remove('-active');

        return this;
    }

    setLoading(loading: boolean = true) {
        this.setActive(true);

        loading
            ? this.element.classList.add('-loading')
            : this.element.classList.remove('-loading');

        return this;
    }

    onFinish(cb: any) {
        this.events.on('finish', cb);
    }

    finish() {
        this.setLoading(false);
        this.element.classList.add('-finished');

        setTimeout(() => {
            fadeOut(this.element, 500, () => {
                this.setActive(false);
                this.events.emit('finish');
            })
        }, 2000);
    }


}

const createMarkup = (): HTMLElement => {
    return AcHtmlElement.create('div').addClass('ac-ajax-loading').addHtml(`
        <div class="ac-ajax-loading__spinner spinner"></div>
        <div class="ac-ajax-loading__icon"><span class="dashicons dashicons-yes-alt"></span></div>
        <div class="ac-ajax-loading__status">Saved</div>
    `).getElement();
}