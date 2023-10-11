<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import AcToggle from "ACUi/element/AcToggle.svelte";

	export let value;
	export let config;

	let label = config.label ?? 'UNKNOWN';
	let checked = false;
	const check = () => {
		value = checked ? 'on' : 'off';
	}


</script>

<ColumnSetting {label}>
	<AcToggle bind:checked={checked} on:input={check}>Hoi</AcToggle>
</ColumnSetting>