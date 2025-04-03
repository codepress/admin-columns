export const fadeOut = (element: HTMLElement, ms: number = 100, cb: Function | null = null, display: string = 'none') => {
    element.style.transition = `opacity ${ms}ms`;
    element.style.opacity = '1';

    setTimeout(() => {
        element.style.opacity = '0';

    }, 100);

    element.addEventListener('transitionend', () => {
        element.style.display = display;
        if (cb) {
            cb.call(this);
        }
    }, {once: true});
}