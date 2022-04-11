import AcHtmlElement from "./html-element";

export const insertAfter = (newNode: HTMLElement, referenceNode: HTMLElement): void => {
    referenceNode?.parentNode?.insertBefore(newNode, referenceNode.nextSibling);
}

export const insertBefore = (newNode: HTMLElement, referenceNode: HTMLElement): void => {
    referenceNode?.parentNode?.insertBefore(newNode, referenceNode);
}

export const createElementFromString = (content: string, baseElement: string = 'div'): HTMLElement => {
    return AcHtmlElement.create(baseElement).addHtml(content).getElement();
}