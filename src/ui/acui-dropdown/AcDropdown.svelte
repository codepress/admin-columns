<script type="ts">

    import {createEventDispatcher, onMount} from "svelte";
    import AcDropdownMenu from "./AcDropdownMenu.svelte";

    export let appendToBody: boolean = false;
    export let closeOnClick: boolean = true;
    export let position: string | null;
    export let maxHeight: string | null;
    export let value;

    const dispatch = createEventDispatcher();

    let opened: boolean = false;
    let trigger: HTMLElement;
    let container: HTMLElement;

    export const toggle = () => {
        if (opened) {
            close();
        } else {
            open();
        }
    }

    const open = async () => {
        opened = true;
        registerCloseHandlers();
    }

    const close = () => {
        opened = false;
        deregisterCloseHandlers();
    }

    const handleEscapeKey = (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            opened = false;
        }
    }

    const handleOutsideClick = (e) => {
        if (container && !container.contains(e.target)) {
            opened = false;
        }
    }

    const registerCloseHandlers = () => {
        document.addEventListener('keydown', handleEscapeKey);
        document.addEventListener('click', handleOutsideClick);
    }

    const deregisterCloseHandlers = () => {
        document.removeEventListener('keydown', handleEscapeKey);
        document.removeEventListener('click', handleOutsideClick);
        document.removeEventListener('focusout', handleOutsideClick);
    }

    const handleSelect = (e) => {
        value = e.detail;
        dispatch('change', value);
        if (opened && closeOnClick) {
            opened = false;
        }
    }

    const handleKeyDown = (e) => {
        if (e.key === 'Enter') {
            toggle();
        }
    }

    onMount(() => {
        if (opened) {
            registerCloseHandlers();
        }
    });

</script>
<div class="acui-dropdown" bind:this={container} on:change={ handleSelect }>
	<div class="acui-dropdown-trigger" on:click={toggle} on:keydown={handleKeyDown} aria-haspopup="true" bind:this={trigger}>
		<slot name="trigger" active={opened}></slot>
	</div>
	{#if opened}
		<AcDropdownMenu {maxHeight} {appendToBody} trigger={trigger} position={position} on:click={handleSelect} on:itemSelect={( e ) => { e.stopPropagation(); handleSelect()}}>
			<slot></slot>
		</AcDropdownMenu>
	{/if}
</div>