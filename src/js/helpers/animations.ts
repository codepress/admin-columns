export const fadeIn = (element: HTMLElement, ms: number = 100, cb: Function = null) => {
    element.style.transition = `transition: opacity ${ms}ms`;
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