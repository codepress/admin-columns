<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import AcToggle from "ACUi/element/AcToggle.svelte";
	import {onMount} from "svelte";

	export let value;
	export let config;
	export let data;

	let label = config.label ?? 'UNKNOWN';
	let checked = false;
	const check = () => {
		value = checked ? 'on' : 'off';
	}

	onMount( () => {
		if ( typeof value === 'undefined' ) {
			value = config.default ? config.default : 'off';
		}
	} );


</script>

<ColumnSetting {label}>
	<AcToggle bind:checked={checked} on:input={check}></AcToggle>
</ColumnSetting>