<script type="ts">
    import {createEventDispatcher} from "svelte";

    export let custom: boolean = false;
    export let href: string = null;

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
	<div class="acui-dropdown-item" role="listitem">
		<slot></slot>
	</div>
{:else}
	{#if href}
		<a class="acui-dropdown-item" on:click={handleClick} on:keydown={handleKeyDown} bind:this={element} {href}>
			<slot></slot>
		</a>
	{:else}
		<div class="acui-dropdown-item" on:click={handleClick} on:keydown={handleKeyDown} tabindex="0" role="button" bind:this={element}>
			<slot></slot>
		</div>
	{/if}

{/if}

