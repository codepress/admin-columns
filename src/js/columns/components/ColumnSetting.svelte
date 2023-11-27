<script lang="ts">
    import ColumnSettings from "./ColumnSettings.svelte";

    export let name: string | undefined;
    export let label: string;
    export let data: any = {};
    export let top: boolean = false;
    export let config: AC.Column.Settings.ColumnSetting|null = null;

    $: hasChildren = config?.children?.length > 0 ?? false;
</script>
<style>


</style>
<div class="acp-column-setting" data-setting={name} class:-align-top={top}>
	<div class="acp-column-setting__label">{label}</div>
	<div class="acp-column-setting__value">
		<slot>

		</slot>
	</div>
</div>
{#if hasChildren}
	<ColumnSettings bind:data={data} settings={config.children} parent={config.name}></ColumnSettings>
{/if}