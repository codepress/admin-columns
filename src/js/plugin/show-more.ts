export const auto_init_show_more = () => {
    document.querySelectorAll('.ac-show-more').forEach((el: HTMLElement) => {
       new ShowMore(el);
    });
}

export default class ShowMore {

    constructor(private element: HTMLElement) {
        this.initEvents();
    }

    initEvents() {
        if (this.isInited()) {
            return;
        }

        if (this.getToggleElement()) {
            this.getToggleElement()?.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                this.toggle();
            });
        }

        this.element.dataset.showMoreInit = 'true';
    }

    getToggleElement(): HTMLElement | null {
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
        this.setToggleText(this.getToggleElement()?.dataset?.less ?? '');
    }

    hide() {
        this.element.classList.remove('-on');
        this.setToggleText(this.getToggleElement()?.dataset?.more ?? '');
    }

    private setToggleText(text: string) {
        let toggle = this.getToggleElement();
        if (toggle) {
            toggle.innerHTML = text;
        }
    }

}