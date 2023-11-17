<script lang="ts">
	import ColumnSetting from "../ColumnSetting.svelte";
	import AcInputGroup from "ACUi/acui-form/AcInputGroup.svelte";

	export let config: AC.Column.Settings.AbstractColumnSetting;
	export let value: string;
</script>

<ColumnSetting label={config.label}>
	<AcInputGroup>
		<input type="text" bind:value={value}>
	</AcInputGroup>
</ColumnSetting>
