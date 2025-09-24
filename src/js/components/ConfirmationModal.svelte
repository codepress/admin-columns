<script lang="ts">
    import {onDestroy, onMount} from "svelte";

    export let message: string;
    export let onConfirm: Function;
    export let onCancel: Function | null = null;
    export let onClose: Function;
    export let lastFocusElement: HTMLElement | null | undefined;

    export let ok;
    export let cancel;

    let okButton: HTMLButtonElement;

    const handleConfirm = () => {
        onConfirm();
        close();
    }

    const handleCancel = () => {
        if (onCancel) onCancel();

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

<div class="ac-confirmation acu-mb-">
	<div class="ac-confirmation__modal">
		<div class="ac-confirmation__modal__content">
			{@html message}
		</div>
		<div class="ac-confirmation__modal__footer">
			<button on:click={handleCancel} class="button">{cancel}</button>
			<button on:click={handleConfirm} class="button button-primary" bind:this={okButton}>{ok}</button>
		</div>
	</div>
</div>