<script type="ts">
    import {createEventDispatcher} from "svelte";

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
<style>
	.acui-dropdown-item {
		margin: 0 2px;
		padding: 5px 20px;
		cursor: pointer;
		display: block;
		white-space: nowrap;
	}

	.acui-dropdown-item:hover,
	.acui-dropdown-item:focus {
		background: var(--ac-primary-color);
		color: #fff;
		border-radius: 5px;
	}
</style>

<a class="acui-dropdown-item" on:click={handleClick} on:keydown={handleKeyDown} tabindex="0" role="list" bind:this={element}>
	<slot></slot>
</a>