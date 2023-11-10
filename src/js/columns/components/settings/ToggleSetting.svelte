<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import AcToggle from "ACUi/element/AcToggle.svelte";
	import {onMount} from "svelte";

	export let data;
	export let config;

	let label = config.label ?? 'UNKNOWN';
	let checked = false;

	const check = () => {
		data[config.key] = checked ? 'on' : 'off';
	}

	onMount( () => {
		if ( typeof data[config.key] === 'undefined' ) {
			data[config.key] = config.default ? config.default : 'off';
		}
	} );

	$: checked = data[config.key] === 'on'

</script>

<ColumnSetting {label}>
	<AcToggle bind:checked={checked} on:input={check}></AcToggle>
</ColumnSetting>