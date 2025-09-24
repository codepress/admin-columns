import ConfirmationModal from "../components/ConfirmationModal.svelte";
import {ModuleConfirmationTranslation} from "../types/admin-columns";
import {getGlobalTranslation} from "../global-translations";

type ConfirmationConfig = {
    message: string,
    confirm: Function,
    oncancel?: Function,
    lastFocus?: HTMLElement | null
    translation?: ModuleConfirmationTranslation
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
                ok: this.config?.translation?.ok ?? getGlobalTranslation().confirmation.ok,
                cancel: this.config?.translation?.cancel ?? getGlobalTranslation().confirmation.cancel,
                message: this.config.message,
                onConfirm: this.config.confirm,
                lastFocusElement: this.config.lastFocus,
                onClose: () => {
                    this.component.$destroy();
                    element.remove()
                },
                onCancel: this.config.oncancel??null
            }
        });
    }
}