<script type="ts">
    import {createEventDispatcher} from "svelte";

    export let ariaRole: string = 'listitem';
    export let custom: boolean = false;

    const dispatch = createEventDispatcher();

    let element: HTMLElement;

    const dispatchSelectItem = () => {
        dispatch('click');
        element.dispatchEvent(new CustomEvent('itemSelect', {bubbles: true}))
    }

    const handleClick = () => {
        dispatchSelectItem();
    }

    const handleKeyDown = (e: KeyboardEvent) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            dispatchSelectItem();
        }
    }

</script>

{#if custom }
	<div class="acui-dropdown-item" tabindex="0" role="{ariaRole}">
		<slot></slot>
	</div>
{:else}
	<a class="acui-dropdown-item" on:click={handleClick} on:keydown={handleKeyDown} tabindex="0" role={ariaRole} bind:this={element}>
		<slot></slot>
	</a>
{/if}

