<script lang="ts">
    import {createEventDispatcher} from "svelte";

    export let label: string;
    export let active: boolean = false;
    export let href: string|undefined|null = null;

    const dispatch = createEventDispatcher();

    const handleClick = (e: MouseEvent) => {
        if (href) {
            window.location.href = href;
            e.preventDefault();
        }

        dispatch('select', '')
    }

    const handleKeyDown = (e: KeyboardEvent) => {
        if (e.key === "ArrowDown") {

            e.preventDefault();
        } else if (e.key === "ArrowUp") {

            e.preventDefault();
        } else if (e.key === "Enter" || e.key === " ") {
            dispatch('select', '')
            e.preventDefault();
        }

    }
</script>
<div class="acui-list-item " class:-active={active} role="menuitem" tabindex="0" on:keydown={handleKeyDown}
	on:click={handleClick}>
	<span class="acui-list-item-content">
		{label}
	</span>
</div>
