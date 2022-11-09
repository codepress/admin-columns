<script lang="ts">
    import {createEventDispatcher, onMount} from "svelte";

    const bodyScrollLock = require('body-scroll-lock');

    export let contentNoPadding: boolean = false;
    export let hideContent: boolean = false;
    export let disableClose: boolean = false;
    export let className = '';
    export let visible = false;
    export let disableScroll = null;

    let element;
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

        if (disableScroll && element) {
            bodyScrollLock.disableBodyScroll(element, {});
        }
    });
</script>
<div class="ac-modal {className}" class:-active={visible} on:click={close} bind:this={element}>
	<div class="ac-modal__dialog" on:click|stopPropagation>
		<div class="ac-modal__dialog__header">
			<slot name="header"></slot>
			{#if !disableClose}
				<button class="ac-modal__dialog__close" on:click={close} disabled='{disableClose}'>
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