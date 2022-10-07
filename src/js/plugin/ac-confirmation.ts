import ConfirmationModal from "../components/ConfirmationModal.svelte";
import {ModuleConfirmationTranslation} from "../types/admin-columns";

type ConfirmationConfig = {
    message: string,
    confirm: Function,
    lastFocus?: HTMLElement | null
    translation: ModuleConfirmationTranslation
}

export default class AcConfirmation {

    config: ConfirmationConfig;
    component: any;

    constructor(config: ConfirmationConfig) {
        this.config = config;
    }

    create() {
        let element = document.createElement('div');
        document.body.appendChild(element);

        this.component = new ConfirmationModal({
            target: element,
            props: {
                ok: this.config?.translation?.ok ?? 'Ok',
                cancel: this.config?.translation?.cancel ?? 'Cancel',
                message: this.config.message,
                onConfirm: this.config.confirm,
                lastFocusElement: this.config.lastFocus,
                onClose: () => {
                    this.component.$destroy();
                    element.remove()
                }
            }
        });
    }
}