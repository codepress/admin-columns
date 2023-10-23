<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import {onMount} from "svelte";

	export let value;
	export let config;

	onMount( () => {
		if ( typeof value === 'undefined' ) {
			value = config.default ? config.default : '';
		}
	} );
</script>

<ColumnSetting label={config.label}>
	<div>
		<input type="text" bind:value={value}>
	</div>
</ColumnSetting>