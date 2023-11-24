<script lang="ts">
    import ColumnSettings from "./ColumnSettings.svelte";

    export let name: string|undefined;
    export let label: string;
    export let children: AC.Column.Settings.ColumnSetting[] = []
	export let data: any = {};
    export let top: boolean = false;
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
{#if children.length > 0}
	<ColumnSettings bind:data={data} settings={children}></ColumnSettings>
{/if}