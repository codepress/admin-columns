<script lang="ts">
	import ColumnSetting from "../ColumnSetting.svelte";
	import Select from "svelte-select"
	import {onMount} from "svelte";

	export let config: AC.Column.Settings.SelectSetting;
	export let value: string;

	let collection: AC.Column.Settings.SettingOption[] = [];
	let selectValue: string;

	onMount( () => {
		collection = config.input.options
	} )

	const changeValue = ( d ) => {
		value = selectValue;
	}

	const groupBy = ( item ) => item.group;
</script>

<ColumnSetting label={config.label}>

		<Select
				--list-max-height="400px"
				class="-acui"
				showChevron
				items={collection}
				value={value}
				clearable={false}
				{groupBy}
				on:change={ changeValue }
				bind:justValue={selectValue}>

		</Select>

</ColumnSetting>