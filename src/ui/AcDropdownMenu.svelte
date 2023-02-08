<script type="ts">
    import {fade} from 'svelte/transition';
</script>
<style>
	.acui-dropdown-menu {
		position: absolute;
		top: 100%;
		background: #fff;
		box-shadow: 0 2px 4px -1px rgb(0 0 0 / 14%);

		min-width: 200px;
		padding: 8px 0;
		z-index: var(--menu-z-index, 20);
	}
</style>

<div class="acui-dropdown-menu" in:fade={{ duration: 100}} out:fade={{ duration: 100}}>
	<div class="acui-dropdown-content" role="list" aria-modal="true" on:itemSelect>
		<slot/>
	</div>
</div>