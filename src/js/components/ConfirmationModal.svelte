<script lang="ts">
    import {onDestroy, onMount} from "svelte";
    import {getTableTranslation} from "../helpers/translations";

    export let message: string;
    export let onConfirm: Function;
    export let onClose: Function;
    export let lastFocusElement: HTMLElement;

    let okButton;

    let i18n = getTableTranslation();

    const confirm = () => {
        onConfirm();
        close();
    }

    const close = () => {
        if (lastFocusElement) {
            lastFocusElement.focus();
        }
        onClose();
    }

    const keyDownHandler = (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            close();
        }
    }

    onMount(() => {
        document.addEventListener('keydown', keyDownHandler);
        okButton.focus();
    });

    onDestroy(() => {
        document.removeEventListener('keydown', keyDownHandler);
    });
</script>

<div class="ac-confirmation">
	<div class="ac-confirmation__modal">
		<div class="ac-confirmation__modal__content">
			{message}
		</div>
		<div class="ac-confirmation__modal__footer">
			<button on:click={close} class="button">{i18n.cancel}</button>
			<button on:click={confirm} class="button button-primary" bind:this={okButton}>{i18n.ok}</button>
		</div>
	</div>
</div>