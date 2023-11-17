<script lang="ts">
	import ColumnSetting from "../ColumnSetting.svelte";
	import {onMount} from "svelte";
	import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

	export let config: AC.Column.Settings.TextSetting;
	export let value: any;

	onMount( () => {
		if ( typeof value === 'undefined' ) {
			value = config.default ? config.default : '';
		}
	} );

</script>

<ColumnSetting label={config.label}>
	<AcInputGroup>
		<input type="text" bind:value={value}>
	</AcInputGroup>
</ColumnSetting>