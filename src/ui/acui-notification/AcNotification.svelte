<script lang="ts">

    import {createEventDispatcher, onMount} from "svelte";

    export let active: boolean = true;
    export let type: 'success' | 'warning' | 'notify' | 'default' = 'default';
    export let closable: boolean = true;
    export let autoClose: boolean = false;
    export let duration: number = 4000;
    export let message: string = '';

    const dispatch = createEventDispatcher();

    $: className = '-' + type;

    const close = () => {
        active = false;
        dispatch( 'close');
	}

	const closeHandle = () => {
        close();
	}

    onMount(() => {
		if( autoClose ){
			setTimeout( () => {
                close();
			}, duration );
		}
    });

</script>
<article
		class="acui-notification {className}"
		class:-closable={closable}
		style:display={ active ? null : 'none'}>
	{#if closable}
		<button aria-label="Close notification" class="acui-notification-close" on:click={closeHandle}>
			<span class="dashicons dashicons-no"></span>
		</button>
	{/if}
	<div class="acui-notification-media">
		<div class="ac-notification-media__left">

		</div>
		<div class="ac-notification-media__content">
			<slot></slot>{message}
		</div>
	</div>
</article>