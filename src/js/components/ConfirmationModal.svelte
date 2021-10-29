<script lang="ts">
    import {onMount, onDestroy} from "svelte";

    export let message: string;
    export let onConfirm: Function;
    export let onClose: Function;

    const confirm = () => {
        onConfirm();
        close();
    }

    const close = () => {
        onClose();
    }

    const keyDownHandler = ( e: KeyboardEvent ) => {
        if( e.key === 'Escape'){
            close();
        }
        if( e.key === 'Enter'){
            console.log( 'EEEE');
        }
	}

    onMount(() => {
        document.addEventListener('keydown', keyDownHandler);
    });

    onDestroy( () => {
        document.removeEventListener('keydown', keyDownHandler);
	});
</script>

<div class="ac-confirmation">
	<div class="ac-confirmation__modal">
		<div class="ac-confirmation__modal__content">
			{message}
		</div>
		<div class="ac-confirmation__modal__footer">
			<button on:click={close} class="button">Cancel</button>
			<button on:click={confirm} class="button button-primary">Ok</button>
		</div>
	</div>
</div>