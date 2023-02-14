export default class AcHtmlElement<T extends HTMLElement = HTMLElement> {

    constructor(private element: T) {
    }

    static find<T extends HTMLElement = HTMLElement>(selector: string): AcHtmlElement<T> | null {
        let element = document.querySelector<T>(selector);

        return element === null ? null : new AcHtmlElement<T>(element);
    }

    static create<U extends HTMLElement = HTMLElement>(el: string): AcHtmlElement<U> {
        return new AcHtmlElement<U>(document.createElement(el) as U);
    }

    getElement(): T {
        return this.element;
    }

    addId(id: string) {
        this.element.id = id;

        return this;
    }

    toggleClass(className: string, add: null | boolean = null) {
        if (add === null) {
            return this.element.classList.contains(className)
                ? this.removeClass(className)
                : this.addClass(className);
        }

        return add
            ? this.addClass(className)
            : this.removeClasses(className);
    }

    addClass(className: string) {
        this.element.classList.add(className);

        return this;
    }

    addClasses(...classNames: string[]) {
        classNames.forEach(className => this.addClass(className));

        return this;
    }

    removeClasses(...classNames: string[]) {
        classNames.forEach(className => this.removeClass(className));

        return this;
    }

    removeClass(className: string) {
        this.element.classList.remove(className);

        return this;
    }

    setAttribute(name: string, value: string) {
        this.element.setAttribute(name, value);

        return this;
    }

    setAttributes(attributes: { [key: string]: string }) {
        Object.keys(attributes).forEach(k => this.setAttribute(k, attributes[k]));

        return this;
    }

    addHtml(html: string) {
        this.element.innerHTML = html;

        return this;
    }

    append(element: HTMLElement) {
        this.element.appendChild(element);

        return this;
    }

    appendFound(selector: string) {
        document.querySelectorAll<HTMLElement>(selector).forEach(el => this.append(el));

        return this;
    }

    appendSelfTo(element: HTMLElement) {
        element.append(this.element);

        return this;
    }

    prepend(element: HTMLElement) {
        this.element.prepend(element);

        return this;
    }

    prependSelfTo(element: HTMLElement) {
        element.prepend(this.element);

        return this;
    }

    css(property: any, value: any) {
        this.element.style[property] = value;

        return this;
    }

    insertAfter(insertedElement: HTMLElement) {
        try {
            this.element.parentElement?.insertBefore(insertedElement, this.element.nextElementSibling);
        } catch (e) {
            console.error("Not able to insert element after current node", this.element);
        }
    }

    insertSelfBefore(referenceNode: HTMLElement) {
        try {
            referenceNode.parentElement?.insertBefore(this.element, referenceNode);
        } catch (e) {
            console.error("Not able to insert element before current node", this.element);
        }

        return this;
    }


    insertBefore(insertedElement: HTMLElement) {
        try {
            this.element.parentElement?.insertBefore(insertedElement, this.element);
        } catch (e) {
            console.error("Not able to insert element before current node", this.element);
        }

        return this;
    }

    addEventListener(event: string, listener: EventListener) {
        this.element.addEventListener(event, listener);

        return this;
    }

    addEventListeners(events: Array<string>, listener: EventListener) {
        events.forEach(e => this.addEventListener(e, listener));

        return this;
    }

    $() {
        return this.getElement();
    }

}