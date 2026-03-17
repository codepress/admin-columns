<script lang="ts">
    import ColumnSetting from "../ColumnSetting.svelte";
    import {showColumnInfo} from "../../store";

    export let setting: AC.Column.Settings.AbstractColumnSetting;

    $: items = (setting as any).input?.data ?? [];
</script>

{#if $showColumnInfo && items.length > 0}
	<ColumnSetting label={setting.label} description="">
		<div class="acu-grid acu-gap-x-[20px] acu-gap-y-[6px] acu-items-center acu-w-full" style="grid-template-columns: max-content auto;">
			{#each items as item}
				<strong class="acu-text-[13px] acu-font-semibold acu-text-[#3c434a] acu-whitespace-nowrap">{item.label}</strong>
				<div>
					<code class="acu-inline-block acu-bg-[#f0f0f0] acu-text-[#3c3c3c] acu-px-[10px] acu-py-[3px] acu-rounded acu-text-[12px] acu-font-mono">{item.value}</code>
				</div>
			{/each}
		</div>
	</ColumnSetting>
{/if}
