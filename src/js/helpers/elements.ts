export function insertAfter( newNode: HTMLElement, referenceNode: HTMLElement ): void {
    referenceNode.parentNode.insertBefore( newNode, referenceNode.nextSibling );
}

export function insertBefore( newNode: HTMLElement, referenceNode: HTMLElement ): void {
    referenceNode.parentNode.insertBefore( newNode, referenceNode );
}