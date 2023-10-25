<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import {createEventDispatcher, onMount} from "svelte";

	const dispatch = createEventDispatcher();

	export let data;
	export let config;

	let value;

	const changeValue = () => {
		data[config.key] = value;
		dispatch('change', data );
		console.log('Change it');
	}

	onMount( () => {
		value = data[config.key];
		if ( typeof value === 'undefined' ) {
			value = config.default ? config.default : '';
		}
	} );


</script>

<ColumnSetting label={config.label}>
	<div>
		<input type="text" bind:value={value} on:input={changeValue}>
	</div>
</ColumnSetting>