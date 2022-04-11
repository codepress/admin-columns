export const fadeIn = (element: HTMLElement, ms: number = 100, cb: Function | null = null, display: string = 'block') => {
    element.style.display = display;
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

interface scrollToElementOptions {
    offset?: number
}

export const scrollToElement = (element: HTMLElement, ms: number, options: scrollToElementOptions = {}) => {
    let defaults: scrollToElementOptions = {
        offset: 0
    }
    let settings = Object.assign({}, defaults, options);

    const elementY = element.offsetTop + (settings.offset ?? 0);
    const startingY = window.pageYOffset;
    const diff = elementY - startingY;
    let start: number;

    // Bootstrap our animation - it will get called right before next frame shall be rendered.
    window.requestAnimationFrame(function step(timestamp) {
        if (!start) {
            start = timestamp;
        }

        let time = timestamp - start;
        let percent = Math.min(time / ms, 1);

        window.scrollTo(0, startingY + diff * percent);

        if (time < ms) {
            window.requestAnimationFrame(step);
        }
    });
}