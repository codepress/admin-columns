<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import Select from "svelte-select"
	import {onMount} from "svelte";

	export let config;
	export let value;

	let collection = [];
	let selectValue;

	onMount( () => {
		let options = [];
		Object.keys( config.options ).forEach( k => {
			for ( const [ key, value ] of Object.entries( config.options[ k ].options ) ) {
				options.push( {
					group : config.options[ k ].label,
					value : key,
					label : value
				} )
			}

		} )
		collection = options
	} )

	const changeValue = ( d ) => {
		value = selectValue;
	}

	const groupBy = ( item ) => item.group;
</script>

<ColumnSetting label={config.label}>
	<div style="width:300px">
		<Select items={collection} {groupBy} value={value} on:change={ changeValue } bind:justValue={selectValue}>

		</Select>
	</div>
</ColumnSetting>