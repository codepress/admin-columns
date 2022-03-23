export const AcEl = (el: string | HTMLElement) => {
    return AcHtmlElement.create(el);
}

export default class AcHtmlElement {
    element: HTMLElement

    constructor(el: string | HTMLElement) {
        this.element = el instanceof HTMLElement ? el : document.createElement(el);
    }

    static create(el: string | HTMLElement): AcHtmlElement {
        return new AcHtmlElement(el)
    }

    addId(id: string) {
        this.element.id = id;

        return this;
    }

    addClass(className: string) {
        this.element.classList.add(className);

        return this;
    }

    addClasses(...classNames: string[]) {
        classNames.forEach(className => this.addClass(className));

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

    appendSelfTo(element: HTMLElement) {
        element.append(this.element);

        return this;
    }

    css(property: any, value: any) {
        this.element.style[property] = value;

        return this;
    }

    insertAfter(insertedElement: HTMLElement) {
        try {
            this.element.parentElement.insertBefore(insertedElement, this.element.nextElementSibling);
        } catch (e) {
            console.error("Not able to insert element after current node", this.element);
        }
    }

    insertSelfBefore(referenceNode: HTMLElement) {
        try {
            referenceNode.parentElement.insertBefore(this.element, referenceNode);
        } catch (e) {
            console.error("Not able to insert element before current node", this.element);
        }

        return this;
    }


    insertBefore(insertedElement: HTMLElement) {
        try {
            this.element.parentElement.insertBefore(insertedElement, this.element);
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

}