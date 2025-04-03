export const addEventListenerLive = (eventType: string, elementQuerySelector: string, cb: Function, rootElement: HTMLElement | Document | null = null) => {
    let element = rootElement ? rootElement : document;

    element.addEventListener(eventType, (event: Event) => {
        let qs = document.querySelectorAll(elementQuerySelector);

        if (qs) {
            var element = (<HTMLElement>event.target), index = -1;
            while (element && ((index = Array.prototype.indexOf.call(qs, element)) === -1)) {
                element = element.parentElement as HTMLElement;
            }

            if (index > -1) {
                cb.call(element, event);
            }
        }
    });
}