<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {onMount} from "svelte";

    export let config: any;

    onMount( () => {
        console.log( config )
	})
</script>
<style>
	.ac-setting-message {
		padding: 10px 0;
	}
</style>

	<div class="ac-setting-message">{@html config.input.data.message}</div>
