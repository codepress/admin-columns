<script type="ts">

    import {onMount} from "svelte";
    import AcDropdownMenu from "./AcDropdownMenu.svelte";

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

    onMount(() => {
        if (opened) {
            registerCloseHandlers();
        }
    });

</script>
<style>
	.acui-dropdown {
		position: relative;
	}
</style>
<div class="acui-dropdown" on:click|stopPropagation>
	<div class="acui-dropdown-trigger" on:click={toggle}>
		<slot name="trigger"></slot>
	</div>
	{#if opened}
		<AcDropdownMenu>
			<slot></slot>
		</AcDropdownMenu>
	{/if}
</div>