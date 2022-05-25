import Tooltip from "./tooltips";
import PointerModal from "../components/PointerModal.svelte";

export class Pointer {

    container: HTMLElement
    component: PointerModal
    onScreen: boolean
    width: string | null
    position: string

    constructor(private element: HTMLElement, private target: HTMLElement) {
        this.width = element.dataset.width ?? null;
        this.position = element.dataset.pos ?? 'right';
        this.initEvents();
    }

    initEvents() {
        if (this.element.dataset.acTooltipInit === '1') {
            return;
        }

        this.container = createPointerElement();
        if (this.width) {
            this.container.style.width = `${this.width}px`;
        }
        this.attachModel();
        this.element.dataset.acTooltipInit = '1';

        this.element.addEventListener('mouseenter', () => {
            if (this.onScreen) {
                return;
            }

            this.onScreen = true;

            document.body.appendChild(this.container);

            this.container.style.width = `${this.width}px`;
            this.setPosition();

            setTimeout(() => {
                this.setPosition()
            }, 100);
        });

        this.element.addEventListener('mouseleave', () => {
            this.checkClose();
        });
    }

    setPosition() {
        const bodyOffset = document.body.getBoundingClientRect();
        const viewportOffset = this.element.getBoundingClientRect();
        this.container.style.top = ((viewportOffset.top - bodyOffset.top) + this.element.offsetHeight - (this.container.offsetHeight / 2) + 20) + 'px';

        switch (this.position) {
            case'right_bottom':
                this.container.style.top = ((viewportOffset.top - bodyOffset.top) - this.container.offsetHeight + 100) + 'px';
                this.container.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth + 14) + 'px';
                break;
            case'left':
                this.container.style.left = ((viewportOffset.left - bodyOffset.left) - this.container.offsetWidth - 12) + 'px';
                break;

            default:
                this.container.style.left = ((viewportOffset.left - bodyOffset.left) + this.element.offsetWidth + 10) + 'px';
        }
    }

    checkClose() {
        setTimeout(() => {
            if (!this.component.isOnElement()) {
                this.closeHandler();
            }
        }, 50)
    }

    closeHandler() {
        if (this.onScreen) {
            document.body.removeChild(this.container);
            this.onScreen = false;
        }
    }

    private destroyComponent() {
        if (this.component !== null) {
            this.component.$destroy();
        }
    }

    private attachModel() {
        this.component = new PointerModal({
            target: this.container,
            props: {
                content: this.target.innerHTML,
                position: this.position,
                closeHandler: () => this.checkClose(),
                destroyHandler: () => this.destroyComponent()
            }
        });
    }
}

const createPointerElement = () => {
    const element = document.createElement('div');
    element.classList.add('ac-pointer-modal-container');
    element.style.position = 'absolute';

    return element;
}

export const initPointers = (elements: NodeListOf<HTMLElement> | null = null) => {
    if (!elements) {
        elements = document.querySelectorAll<HTMLElement>('.ac-pointer')
    }

    elements.forEach(element => {
        let relElement = document.querySelector<HTMLElement>(`#${element.getAttribute('rel')}` ?? '#n');

        if (relElement) {
            new Pointer(element, relElement);
        }

    });

    new Tooltip();
};