<script lang="ts">
    import ColumnSettings from "./ColumnSettings.svelte";

    export let name: string | undefined;
    export let description: string;
    export let label: string;
    export let data: any = {};
    export let top: boolean = false;
    export let config: AC.Column.Settings.ColumnSetting | null = null;

    $:isParent = config?.is_parent ?? false;
</script>
<style>


</style>
<div class="acp-column-setting" data-setting={name} class:-align-top={top}>
	<div class="acp-column-setting__label">{label}</div>
	<div class="acp-column-setting__value">
		<slot>

		</slot>
		{#if description}
			<small class="acp-column-setting__description">{description}</small>
		{/if}
		{#if config && config.children && isParent}
			<div class="ac-column-settings -subsettings">
				<ColumnSettings bind:data={data} settings={config.children} parent={config.name}></ColumnSettings>
			</div>
		{/if}
	</div>
</div>
{#if config && config.children && !isParent }
	<ColumnSettings bind:data={data} settings={config.children} parent={config.name}></ColumnSettings>
{/if}