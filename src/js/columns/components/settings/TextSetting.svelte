<script>
	import ColumnSetting from "../ColumnSetting.svelte";

	export let value;
</script>

<ColumnSetting label="BLA">
	<div>
		<input type="text" bind:value={value}>
	</div>
</ColumnSetting>