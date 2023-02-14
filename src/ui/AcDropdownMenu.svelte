<script type="ts">
    import {fade} from 'svelte/transition';

    export let maxHeight: string = null;
</script>
<div class="acui-dropdown-menu" in:fade={{ duration: 100}} out:fade={{ duration: 100}} style:max-height={maxHeight}>
	<div class="acui-dropdown-content" role="list" aria-modal="true" on:itemSelect>
		<slot/>
	</div>
</div>