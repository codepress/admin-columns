<script lang="ts">

    import {createEventDispatcher, onMount} from "svelte";
    import AcDropdownMenu from "./AcDropdownMenu.svelte";

    export let customClass: string = null;
    export let appendToBody: boolean = false;
    export let closeOnClick: boolean = true;
    export let position: string | null = null;
    export let maxHeight: string | null = null;
    export let value: any | null | undefined = null;

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

    export const open = async () => {
        opened = true;
        registerCloseHandlers();
        dispatch('open');
    }

    export const close = () => {
        opened = false;
        deregisterCloseHandlers();
        dispatch('close');
    }

    const handleEscapeKey = (e: KeyboardEvent) => {
        if (e.key === 'Escape') {
            close();
        }

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            focusNextItem();
        }

        if (e.key === 'ArrowUp') {
            e.preventDefault();
            focusPrevItem();
        }
    }

    const getTabElements = () => {
        return Array.from(container
            .querySelectorAll<HTMLElement>('a, button, input, textarea, select, details, [tabindex]'))
            .filter(element => element.tabIndex > -1)
            .sort((a, b) => a.tabIndex > b.tabIndex ? -1 : 1)
    }

    const moveFocus = (move: number = 1) => {
        const focus = document.activeElement as HTMLElement;
        const items = getTabElements();

        if (items.length === 0) {
            return;
        }

        const index = items.indexOf(focus);

        if (index) {
            items[index + move]?.focus();
        }
    }

    const focusNextItem = () => {
        moveFocus(1);
    }

    const focusPrevItem = () => {
        moveFocus(-1);
    }

    const handleOutsideClick = (e) => {
        if (container && !container.contains(e.target)) {
            close();
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
            e.preventDefault();
            toggle();
        }
    }

    onMount(() => {
        if (opened) {
            registerCloseHandlers();
        }

        container.addEventListener('change', handleSelect);
    });

    let classes = ['acui-dropdown', customClass];

</script>

<div class={classes.join(' ')} bind:this={container} on:change={handleSelect}>
	<div class="acui-dropdown-trigger" on:click|stopPropagation={toggle} on:keydown={handleKeyDown}
		aria-haspopup="true" bind:this={trigger} role="button" tabindex="0">
		<slot name="trigger" active={opened}></slot>
	</div>
	{#if opened}
		<AcDropdownMenu {maxHeight} {appendToBody} trigger={trigger} position={position} on:click={handleSelect}
			on:itemSelect={( e ) => { e.stopPropagation(); handleSelect(e)}}>
			<slot></slot>
		</AcDropdownMenu>
	{/if}
</div>
