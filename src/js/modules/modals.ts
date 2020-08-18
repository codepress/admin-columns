import Modal from './modal';

export default class Modals {

    private modals: { [key: string]: Modal }
    number: number;
    defaults: any;

    constructor() {
        this.modals = {};
        this.number = 0;
        this.defaults = {
            modal: Modal
        }
        this.initGlobalEvents();
    }

    register(modal: Modal, key = '') {
        if (!key) {
            key = 'm' + this.number;
        }

        this.modals[key] = modal;
        this.number++;

        return modal;
    }

    get(key: string): Modal {
        return this.modals.hasOwnProperty(key) ? this.modals[key] : null;
    }

    open(key: string) {
        if (this.get(key)) {
            this.get(key).open()
        }
    }

    close(key: string) {
        if (this.get(key)) {
            this.get(key).close()
        }
    }

    closeAll() {
        for (let key in this.modals) {
            this.close(key);
        }
    }

    initGlobalEvents() {
        document.addEventListener('click', (e: MouseEvent) => {
            let target = e.target as HTMLElement;
            if (target.dataset.acModal) {
                e.preventDefault();

                this.open(target.dataset.acModal);
            }
        });
    }

}