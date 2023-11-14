<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import AcToggle from "ACUi/element/AcToggle.svelte";
	import {afterUpdate, onMount} from "svelte";

	export let data;
	export let value;
	export let config;

	let label = config.label ?? '';
	let checked = false;

	const check = ( e ) => {
		value = e.detail ? 'on' : 'off';
	}

	afterUpdate( () => {
		checked = value === 'on';
	} )

	onMount( () => {
		if ( value === 'undefined' ) {
			value = config.default ? config.default : 'off';
		}

		checked = value === 'on';
	} );

</script>

<ColumnSetting {label}>
	<AcToggle checked={checked} on:input={check}></AcToggle>
</ColumnSetting>