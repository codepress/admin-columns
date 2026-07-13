<script lang="ts">
    import { AcIcon, AcReferencedTooltip } from "ACUi/index";
    import { getGlobalTranslation } from "../../global-translations";

    export let description: string | null = null;
    export let label: string;
    export let extraClass: string = '';
    export let isSubComponent: boolean = false;
    export let attributes: null | Record<string, any> = null;
	export let setting: string|null = null;
    export let error: boolean = false;

    const requiredMessage = getGlobalTranslation()?.validation?.required ?? 'This field is required.';

    // Layout adapts to the width of the nearest `/columnrow` container (set by
    // whoever renders the settings group), not the viewport. This keeps the row
    // stacked inside a narrow host (e.g. a Data Sources relation card) while it
    // goes side-by-side in the wide column panel. See PreferenceSection for the
    // same @container idiom.
    const containerClass = () => {
        const base = ['acp-column-setting', '@lg/columnrow:acu-flex', 'acu-px-6', 'acu-mb-2'];
        if (isSubComponent) base.push('acu-flex-col');
        if (extraClass) base.push(extraClass);
        return base.join(' ');
    };

    const labelClass = isSubComponent
        ? 'acp-column-setting__label acu-font-semibold @lg/columnrow:acu-pt-1 @lg/columnrow:acu-w-[200px]'
        : 'acp-column-setting__label acu-font-semibold @lg/columnrow:acu-py-2 @lg/columnrow:acu-w-[200px] acu-flex-shrink-0 acu-flex acu-mr-2';

    const valueClass = isSubComponent
        ? 'acp-column-setting__value acu-py-1'
        : 'acp-column-setting__value acu-flex-grow acu-py-1';
</script>

<div class={containerClass()} data-setting={setting}>
	<div class={labelClass}>
		<span class={isSubComponent ? '' : 'acu-flex-grow'}>{@html label}{#if attributes && attributes['required']}<span
					style="color:#d63638" aria-hidden="true" title="Required">&nbsp;*</span>{/if}</span>
		{#if !isSubComponent && attributes && attributes['help-ref']}
			<AcReferencedTooltip reference={attributes['help-ref']} position="right" closeDelay={300}>
				<span class="acu-cursor-pointer"><AcIcon icon="question" size="sm" /></span>
			</AcReferencedTooltip>
		{/if}
	</div>

	<div class="{valueClass}{error ? ' -error' : ''}">
		<slot />
		{#if error}
			<small class="acp-column-setting__error acu-block acu-py-1 acu-text-[12px]" style="color:#d63638">
				{requiredMessage}
			</small>
		{:else if description}
			<small class="acp-column-setting__description acu-block acu-py-1 acu-text-[#888] acu-text-[12px]">
				{@html description}
			</small>
		{/if}
	</div>
</div>

<style>
	.acp-column-setting__value.-error :global(input),
	.acp-column-setting__value.-error :global(select),
	.acp-column-setting__value.-error :global(textarea) {
		border-color: #d63638;
		box-shadow: 0 0 0 1px #d63638;
	}
</style>