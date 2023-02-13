<script type="ts">

    import {onMount} from "svelte";
    import AcDropdownMenu from "./AcDropdownMenu.svelte";

    export let closeOnClick: boolean = true;
    export let position: string|null;

    let opened: boolean = false;

    const toggle = () => {
        if (opened) {
            close();
        } else {
            open();
        }
    }

    const open = () => {
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

    const handleOutsideClick = () => {
        opened = false;
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

    const handleSelect = () => {
        if (opened && closeOnClick) {
            opened = false;
        }
    }

    const handleKeyDown = ( e ) => {
        if( e.key === 'Enter' ){
            toggle();
		}
    }

    onMount(() => {
        if (opened) {
            registerCloseHandlers();
        }
    });

</script>
<div class="acui-dropdown">
	<div class="acui-dropdown-trigger" on:click|stopPropagation={toggle} on:keydown={handleKeyDown}>
		<slot name="trigger" active={opened}></slot>
	</div>
	{#if opened}
		<AcDropdownMenu position={position} on:click={handleSelect} on:itemSelect={( e ) => { e.stopPropagation(); handleSelect()}}>
			<slot></slot>
		</AcDropdownMenu>
	{/if}
</div>