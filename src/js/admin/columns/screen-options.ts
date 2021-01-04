export default class InfoScreenOption {
    input: HTMLInputElement
    toggleClass: string
    container: HTMLElement

    constructor(input: HTMLInputElement, toggleClass: string, container: HTMLElement) {
        this.input = input;
        this.toggleClass = toggleClass;
        this.container = container;
        this.initEvents();
    }

    initEvents() {
        this.input.addEventListener('change', () => {
            this.input.checked
                ? this.container.classList.add(this.toggleClass)
                : this.container.classList.remove(this.toggleClass)
        });
    }
}