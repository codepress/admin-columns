<script lang="ts">
    import {onMount} from "svelte";

    export let config: any;

    onMount(() => {

    })
</script>
<style>
	.ac-setting-message {
		padding: 5px 0;
	}
</style>

<div class="ac-setting-message">{@html config.input.data.message}</div>
