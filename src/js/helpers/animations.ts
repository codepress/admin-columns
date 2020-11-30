export const fadeIn = (element: HTMLElement, ms: number = 100, cb: Function = null) => {
    element.style.transition = `opacity ${ms}ms`;
    element.style.opacity = '0';

    setTimeout(() => {
        element.style.opacity = '1';
    }, 100);
    if (cb) {
        element.addEventListener('transitionend', () => {
            cb.call(this);
        }, {once: true});
    }

}

export const fadeOut = (element: HTMLElement, ms: number = 100, cb: Function = null) => {
    element.style.transition = `opacity ${ms}ms`;
    element.style.opacity = '1';

    setTimeout(() => {
        element.style.opacity = '0';
    }, 100);
    if (cb) {
        element.addEventListener('transitionend', () => {
            cb.call(this);
        }, {once: true});
    }

}