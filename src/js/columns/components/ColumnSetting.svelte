<script lang="ts">
    import ColumnSettings from "./ColumnSettings.svelte";

    export let label: string;
    export let children: AC.Column.Settings.ColumnSetting[] = []
	export let data: any = {};
</script>
<style>


</style>
<div class="acp-column-setting">
	<div class="acp-column-setting__label">{label}</div>
	<div class="acp-column-setting__value">
		<slot>

		</slot>
	</div>
</div>
{#if children.length > 0}
	<ColumnSettings bind:data={data} settings={children}></ColumnSettings>
{/if}