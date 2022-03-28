export const auto_init_show_more = () => {
    document.querySelectorAll('.ac-show-more').forEach((el: HTMLElement) => {
        new ShowMore(el);
    });
}

export default class ShowMore {

    element: HTMLElement

    constructor(el: HTMLElement) {
        this.element = el;
        this.initEvents();
    }

    initEvents() {
        if (this.isInited()) {
            return;
        }

        if (this.getToggler()) {
            this.getToggler().addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                this.toggle();
            });
        }

        this.element.dataset.showMoreInit = 'true';
    }

    getToggler(): HTMLElement {
        return this.element.querySelector('.ac-show-more__toggle')!;
    }

    isInited(): boolean {
        return this.element.dataset.showMoreInit === 'true';
    }

    toggle() {
        if (this.element.classList.contains('-on')) {
            this.hide();
        } else {
            this.show();
        }
    }

    show() {
        this.element.classList.add('-on');
        this.getToggler().innerHTML = this.getToggler().dataset.less;
    }

    hide() {
        this.element.classList.remove('-on');
        this.getToggler().innerHTML = this.getToggler().dataset.more;
    }

}