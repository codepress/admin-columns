<script lang="ts">
    import {createEventDispatcher, onDestroy} from "svelte";
    import {fade} from 'svelte/transition';
    import {getGlobalTranslation} from "../js/global-translations";

    export let message: string | null = null;
    export let confirmLabel: string | null = null;
    export let cancelLabel: string | null = null;
    export let open: boolean = false;
    export let position: 'top' | 'bottom' = 'bottom';
    export let customClass: string = '';

    const dispatch = createEventDispatcher();

    let triggerEl: HTMLElement;
    let popoverEl: HTMLElement;

    $: resolvedMessage = message ?? getGlobalTranslation().confirmation.default_message;
    $: resolvedConfirmLabel = confirmLabel ?? getGlobalTranslation().confirmation.ok;
    $: resolvedCancelLabel = cancelLabel ?? getGlobalTranslation().confirmation.cancel;

    export const show = () => {
        open = true;
    };

    export const hide = () => {
        open = false;
    };

    const handleConfirm = () => {
        open = false;
        dispatch('confirm');
    };

    const handleCancel = () => {
        open = false;
        dispatch('cancel');
    };

    const handleKeyDown = (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            handleCancel();
        }
    };

    const handleClickOutside = (e: MouseEvent) => {
        const target = e.target as Node;
        if (
            popoverEl && !popoverEl.contains(target) &&
            triggerEl && !triggerEl.contains(target)
        ) {
            handleCancel();
        }
    };

    const registerCloseHandlers = () => {
        document.addEventListener('keydown', handleKeyDown);
        // Defer click-outside to avoid catching the opening click
        setTimeout(() => document.addEventListener('click', handleClickOutside), 0);
    };

    const deregisterCloseHandlers = () => {
        document.removeEventListener('keydown', handleKeyDown);
        document.removeEventListener('click', handleClickOutside);
    };

    $: open ? registerCloseHandlers() : deregisterCloseHandlers();

    onDestroy(deregisterCloseHandlers);
</script>

<div class="acui-inline-confirmation {customClass}" bind:this={triggerEl}>
    <slot/>

    {#if open}
        <div
                class="acui-inline-confirmation__popover"
                class:is-top={position === 'top'}
                class:is-bottom={position === 'bottom'}
                bind:this={popoverEl}
                in:fade={{duration: 150}}
                out:fade={{duration: 150}}
                role="dialog"
                aria-modal="true"
        >
            <span class="acui-inline-confirmation__message">{@html resolvedMessage}</span>
            <button class="acui-inline-confirmation__confirm" on:click={handleConfirm}>
                {resolvedConfirmLabel}
            </button>
            <button class="acui-inline-confirmation__cancel" on:click={handleCancel}>
                {resolvedCancelLabel}
            </button>
            <div class="acui-inline-confirmation__arrow" aria-hidden="true"></div>
        </div>
    {/if}
</div>
