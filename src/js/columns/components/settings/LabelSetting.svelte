<script>
	import ColumnSetting from "../ColumnSetting.svelte";

	export let value;
</script>

<ColumnSetting>
	<div>
		<input type="" bind:value={value}>
	</div>
</ColumnSetting>
