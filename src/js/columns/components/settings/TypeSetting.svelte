<script>
	import ColumnSetting from "../ColumnSetting.svelte";

	export let value;
</script>

<ColumnSetting label="Type">
	<div>
		<select bind:value={value}>
			<option value="select">Select</option>
			<option value="title">Title</option>
			<option value="date">Date</option>
			<option value="column-actions">Action</option>
		</select>
	</div>
</ColumnSetting>
