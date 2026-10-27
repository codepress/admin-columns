export const initAcTooltips = () => {
    document.querySelectorAll('.ac-tooltip').forEach(el => el.remove());
    document.querySelectorAll<HTMLElement>('[data-ac-tip]').forEach((element) => {
        new Tooltip(element);
    });
}

export class Tooltip {

    element: HTMLElement
    content: string
    tip: HTMLElement;

    constructor(el: HTMLElement, content: string = '') {
        this.element = el;
        this.content = content ? content : el.dataset.acTip as string;
        this.tip = createTooltip(this.content);

        this.initEvents();
    }

    initEvents() {
        if (this.element.dataset.acTooltipInit === '1') {
            return;
        }

        this.element.dataset.acTooltipInit = '1';

        this.element.addEventListener('mouseenter', () => {
            const bodyOffset = document.body.getBoundingClientRect();
            const viewportOffset = this.element.getBoundingClientRect();
            document.body.appendChild(this.tip);

            this.tip.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth / 2) + 'px';
            this.tip.style.top = ((viewportOffset.top - bodyOffset.top) + this.element.offsetHeight) + 'px';
            this.tip.classList.add('hover');
        });

        this.element.addEventListener('mouseleave', () => {
            this.tip.classList.remove('hover');

            if (document.body.contains(this.tip)) {
                document.body.removeChild(this.tip);
            }
        });

        this.element.removeAttribute('title');
    }

}


// Formatting tags our own tooltips use. Everything else is reduced to its text.
const ALLOWED_TOOLTIP_TAGS = ['BR', 'EM', 'STRONG', 'B', 'I', 'CODE', 'SMALL', 'SPAN'];

// Tooltip content can carry attacker-controlled data (e.g. a comment User-Agent),
// so markup is rebuilt from an allowlist with all attributes dropped instead of
// being trusted as HTML. A <template> parses the input inertly: no resource loads
// and no scripts run while we read it.
const sanitizeTooltipContent = (content: string): DocumentFragment => {
    const template = document.createElement('template');
    template.innerHTML = content;

    const clean = (source: Node, target: Node): void => {
        source.childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE) {
                target.appendChild(document.createTextNode(node.textContent ?? ''));
                return;
            }

            if (node.nodeType !== Node.ELEMENT_NODE) {
                return;
            }

            const element = node as HTMLElement;

            if (ALLOWED_TOOLTIP_TAGS.includes(element.tagName)) {
                const safe = document.createElement(element.tagName);
                clean(element, safe);
                target.appendChild(safe);
                return;
            }

            // Disallowed element: keep its text, drop the element and its attributes.
            clean(element, target);
        });
    };

    const fragment = document.createDocumentFragment();
    clean(template.content, fragment);

    return fragment;
}

const createTooltip = (content: string) => {
    let tip = document.createElement('div');
    tip.classList.add('ac-tooltip');
    tip.appendChild(sanitizeTooltipContent(content));

    return tip;
}