declare global {
    interface Window { CustomEvent: any; }
}

export const polyfillCustomEvent = () => {
    if (typeof window.CustomEvent === "function") {
        return false;
    }

    function CustomEvent(event: any, params: any) {
        params = params || {bubbles: false, cancelable: false, detail: undefined};
        let evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    }

    CustomEvent.prototype = window.Event.prototype;

    window.CustomEvent = CustomEvent;
}