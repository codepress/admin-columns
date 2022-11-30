<script lang="ts">
    import {onDestroy, onMount} from "svelte";

    export let message: string;
    export let onConfirm: Function;
    export let onClose: Function;
    export let lastFocusElement: HTMLElement;

    export let ok;
    export let cancel;

    let okButton;

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
			<button on:click={close} class="button">{cancel}</button>
			<button on:click={confirm} class="button button-primary" bind:this={okButton}>{ok}</button>
		</div>
	</div>
</div>