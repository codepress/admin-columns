<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import {createEventDispatcher, onMount} from "svelte";

	const dispatch = createEventDispatcher();

	export let data;
	export let config;

	let value;

	onMount( () => {
		value = data[config.key];
		if ( typeof value === 'undefined' ) {
			value = config.default ? config.default : '';
		}
	} );


</script>

<ColumnSetting label={config.label}>
	<div>
		<input type="text" bind:value={data[config.key]}>
	</div>
</ColumnSetting>