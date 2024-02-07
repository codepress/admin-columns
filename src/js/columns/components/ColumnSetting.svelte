<script lang="ts">
    import ColumnSettings from "./ColumnSettings.svelte";

    export let name: string;
    export let description: string;
    export let label: string;
    export let data: any = {};
    export let config: AC.Column.Settings.ColumnSetting | null = null;

    $:isParent = config?.is_parent ?? false;
</script>
<style>


</style>
<div class="acp-column-setting lg:acu-flex acu-px-6 acu-mb-2" data-setting={name}>

	<div class="acp-column-setting__label acu-font-semibold lg:acu-py-2 lg:acu-w-[200px]">
		{label}
	</div>
	<div class="acp-column-setting__value acu-flex-grow acu-py-1">

		<slot>

		</slot>

		{#if description}
			<small class="acp-column-setting__description acu-block acu-py-1 acu-text-[#888] acu-text-[12px]">{description}</small>
		{/if}

		{#if config && config.children && isParent}
			<div class="ac-column-settings -subsettings">
				<ColumnSettings bind:data={data} settings={config.children} parent={config.name}></ColumnSettings>
			</div>
		{/if}

	</div>
</div>