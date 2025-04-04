export default class Notice {
    element: HTMLElement;
    message: string;
    dismissible: Boolean;

    constructor() {
        this.element = document.createElement('div');
        this.element.classList.add('notice');
        this.dismissible = false;
    }

    renderDismiss() {
        const button = document.createElement('button');

        button.classList.add('notice-dismiss');
        button.setAttribute('type', 'button');
        button.insertAdjacentHTML('beforeend', `<span class="screen-reader-text">Dismiss this notice.</span>`);

        button.addEventListener('click', e => {
            e.preventDefault();
            this.element.remove();
        });

        this.element.classList.add('is-dismissible');
        this.element.insertAdjacentElement('beforeend', button);
    }

    renderContent() {
        this.element.insertAdjacentHTML('afterbegin', this.message);
    }

    addClass(className: string) {
        this.element.classList.add(className);

        return this;
    }

    render() {
        this.element.innerHTML = '';
        this.renderContent();
        if (this.dismissible) {
            this.renderDismiss();
        }

        return this.element;
    }

}