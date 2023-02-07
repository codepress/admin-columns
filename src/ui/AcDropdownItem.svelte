<script type="ts">
	import {createEventDispatcher} from "svelte";

    const dispatch = createEventDispatcher();

    const handleClick = () => {
        dispatch('click');
	}

</script>
<style>
	.acui-dropdown-item {
		padding: 5px 10px;
	}
</style>

<div class="acui-dropdown-item" on:click|preventDefault>
	<slot></slot>
</div>