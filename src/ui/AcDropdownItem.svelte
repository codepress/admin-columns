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

<a class="acui-dropdown-item" on:click={handleClick} on:keydown={handleKeyDown} tabindex="0" role="list" bind:this={element}>
	<slot></slot>
</a>