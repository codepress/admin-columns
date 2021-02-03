let Cookies = require('js-cookie');

export default class AcSection {

    element: HTMLElement

    constructor(el: HTMLElement) {
        this.element = el;
        this.init();
    }

    init() {
        if (this.element.classList.contains('-closable')) {
            const header = this.element.querySelector('.ac-section__header');

            if (header) {
                header.addEventListener('click', () => {
                    this.toggle();
                });
            }

            if (this.isStorable()) {
                let setting = Cookies.get(this.getCookieKey());

                if (setting !== undefined) {
                    (parseInt(setting) === 1) ? this.open : this.close();
                }
            }
        }

    }

    getCookieKey() {
        return `ac-section_${this.getSectionId()}`
    }

    getSectionId() {
        return this.element.dataset.section;
    }

    isStorable() {
        return typeof this.element.dataset.section !== 'undefined';
    }

    toggle() {
        this.isOpen() ? this.close() : this.open();
    }

    isOpen() {
        return !this.element.classList.contains('-closed');
    }

    open() {
        this.element.classList.remove('-closed');
        if (this.isStorable()) {
            Cookies.set(this.getCookieKey(), 1);
        }
    }

    close() {
        this.element.classList.add('-closed');
        if (this.isStorable()) {
            Cookies.set(this.getCookieKey(), 0);
        }
    }

}