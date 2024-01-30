<script lang="ts">

    import {createEventDispatcher, onMount} from "svelte";
    import AcIcon from "../AcIcon.svelte";

    export let active: boolean = true;
    export let type: 'success' | 'warning' | 'error' | 'notify' | 'default' = 'default';
    export let closable: boolean = true;
    export let autoClose: boolean = false;
    export let duration: number = 4000;
    export let message: string = '';
    export let hideIcon: boolean = false;

    const dispatch = createEventDispatcher();

    const close = () => {
        active = false;
        dispatch('close');
    }

    const closeHandle = () => {
        close();
    }

    const determineIcon = (type: string) => {
        switch (type) {
            case 'success':
                return 'yes-alt';
            case 'warning':
                return 'warning';
            case 'error':
                return 'dismiss';
            case 'notify':
                return 'bell';
            default:
                return 'info';
        }
    }

    let icon = determineIcon(type);

    onMount(() => {
        if (autoClose) {
            setTimeout(() => {
                close();
            }, duration);
        }

        icon = determineIcon(type);

    });

    $: className = '-' + type;

</script>
<article
	class="acui-notification {className}"
	class:-closable={closable}
	style:display={ active ? null : 'none'}>
	{#if closable && ! autoClose }
		<button aria-label="Close notification" class="acui-notification-close" on:click={closeHandle}>
			<span class="dashicons dashicons-no"></span>
		</button>
	{/if}
	<div class="acui-notification-media">
		{#if ! hideIcon}
			<div class="ac-notification-media__left">
				<AcIcon icon={icon} pack="dashicons"/>
			</div>
		{/if}
		<div class="ac-notification-media__content">
			<slot></slot>{@html message}
		</div>
	</div>
</article>