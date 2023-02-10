<script type="ts">
    import {fade} from 'svelte/transition';

    export let position: string = 'bottom-right';
</script>
<style>
	.acui-dropdown-menu {
		position: absolute;
		top: 100%;
		background: #FFFFFF;
		box-shadow: 0 9px 24px 0 rgba(15,15,15,0.20), 0 3px 6px 0 rgba(15,15,15,0.10), 0 0 0 1px rgba(15,15,15,0.05);
		border-radius: 2px;

		min-width: 200px;
		padding: 8px 0;
		z-index: var(--menu-z-index, 20);
	}

	.acui-dropdown-menu.-bottom-left {
		right: 0;
	}
</style>

<div class="acui-dropdown-menu" class:-bottom-left={position ==='bottom-left'} in:fade={{ duration: 100}} out:fade={{ duration: 100}}>
	<div class="acui-dropdown-content" role="list" aria-modal="true" on:itemSelect>
		<slot/>
	</div>
</div>