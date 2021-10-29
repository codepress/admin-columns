import ConfirmationModal from "../components/ConfirmationModal.svelte";

export default class AcConfirmation {

    message: string
    onConfirm: Function
    component: any;

    constructor(message: string, cb: Function) {
        this.message = message;
        this.onConfirm = cb;
    }

    create() {
        let element = document.createElement('div');
        document.body.appendChild(element);

        this.component = new ConfirmationModal({
            target: element,
            props: {
                message: this.message,
                onConfirm: this.onConfirm,
                onClose: () => {
                    this.component.$destroy();
                    element.remove()
                }
            }
        });
    }
}