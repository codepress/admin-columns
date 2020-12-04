export const insertAfter = (newNode: HTMLElement, referenceNode: HTMLElement): void => {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

export const insertBefore = (newNode: HTMLElement, referenceNode: HTMLElement): void => {
    referenceNode.parentNode.insertBefore(newNode, referenceNode);
}

export const createElementFromString = (content: string, baseElement: string = 'div'): HTMLElement => {
    let element = document.createElement(baseElement);
    element.innerHTML = content;

    return element;
}

function isInViewport(element: HTMLElement): boolean {
    var rect = element.getBoundingClientRect();

    return (
        rect.top >= 0 && rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}