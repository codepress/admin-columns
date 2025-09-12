<script lang="ts">
    import {createEventDispatcher, onMount} from "svelte";

    const bodyScrollLock = require('body-scroll-lock');

    export let contentNoPadding: boolean = false;
    export let hideContent: boolean = false;
    export let disableClose: boolean = false;
    export let className: string = '';
    export let dialogClass: string = '';
    export let visible: boolean = false;
    export let disableScroll: boolean = false;
    export let appendToBody: boolean = false;

    let element: HTMLElement | null = null;
    const dispatch = createEventDispatcher();

    export const FreeScrollLock = () => {
        if (disableScroll && element) {
            bodyScrollLock.enableBodyScroll(element);
        }
    }

    const close = () => {
        if (disableClose) {
            return;
        }

        dispatch('close');
        FreeScrollLock();
    }

    onMount(() => {
        document.addEventListener('keyup', (e) => {
            if ('Escape' === e.key) {
                close();
            }
        });

        if( appendToBody && element){
            document.body.append(element);
		}

        if (disableScroll && element) {
            bodyScrollLock.disableBodyScroll(element, {});
        }
    });
</script>

<!-- svelte-ignore a11y-click-events-have-key-events -->
<div class="ac-modal {className}" class:-active={visible} on:click={close} bind:this={element} role="none">
	<div class="ac-modal__dialog {dialogClass}" on:click|stopPropagation role="none">
		<div class="ac-modal__dialog__header">
			<slot name="header"></slot>
			{#if !disableClose}
				<button class="ac-modal__dialog__close" on:click|preventDefault={close} disabled='{disableClose}'>
					<span class="dashicons dashicons-no-alt"></span>
				</button>
			{/if}
		</div>

		<slot name="before_content"></slot>

		{#if $$slots.content && !hideContent }
			<div class="ac-modal__dialog__content" class:-p0={contentNoPadding}>
				<slot name="content"></slot>
			</div>
		{/if}

		<slot name="after_content"></slot>

		{#if $$slots.footer }
			<div class="ac-modal__dialog__footer">
				<slot name="footer"></slot>
			</div>
		{/if}

	</div>

</div>