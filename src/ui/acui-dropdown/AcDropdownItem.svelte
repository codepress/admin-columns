<script lang="ts">
    import {createEventDispatcher} from "svelte";

    export let custom: boolean = false;
    export let href: string = null;
    export let value: string | null = null;

    const dispatch = createEventDispatcher();

    let element: HTMLElement;

    const dispatchSelectItem = () => {
        if (value !== null) {
            element.closest('.acui-dropdown')?.dispatchEvent(new CustomEvent('itemSelect', {bubbles: true, detail: value}));
        }
    }

    const handleClick = () => {
        dispatchSelectItem();
        dispatch('click', value);
    }

    const handleKeyDown = (e: KeyboardEvent) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            dispatchSelectItem();
            dispatch('click', value);
        }
    }
</script>

{#if custom }
	<div class="acui-dropdown-item" role="listitem" bind:this={element}>
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