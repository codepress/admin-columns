<script>
	import ColumnSetting from "../ColumnSetting.svelte";
	import Select from "svelte-select"
	import {onMount} from "svelte";
	import {getColumnSettings} from "../../ajax/ajax";

	export let data;
	export let config;
	export let columnConfig;

	let collection = [];
	let selectValue;

	onMount( () => {
		let options = [];
/*		Object.keys( config.options ).forEach( k => {
			for ( const [ key, value ] of Object.entries( config.options[ k ].options ) ) {
				options.push( {
					group : config.options[ k ].title,
					value : key,
					label : value
				} );
			}
		} )*/
		collection = options
	} )

	const changeValue = ( d ) => {
		data[ 'type' ] = selectValue;

		getColumnSettings( 'post', selectValue ).then( response => {
			columnConfig = response.data.data.columns.settings;
		} );

	}

	const groupBy = ( item ) => item.group;
</script>

<ColumnSetting label={config.label}>
	<Select class="-acui"
			--list-max-height="400px"
			showChevron
			clearable={false}
			items={collection}
			{groupBy}
			value={data['type']}
			on:change={ changeValue }
			bind:justValue={selectValue}>

	</Select>
</ColumnSetting>