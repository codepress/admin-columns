<script type="ts">
    import {fade} from 'svelte/transition';

    export let position: string = 'bottom-right';
</script>

<div class="acui-dropdown-menu" class:-bottom-left={position ==='bottom-left'} in:fade={{ duration: 100}} out:fade={{ duration: 100}}>
	<div class="acui-dropdown-content" role="list" aria-modal="true" on:itemSelect>
		<slot/>
	</div>
</div>