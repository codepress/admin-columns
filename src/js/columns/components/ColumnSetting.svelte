<script lang="ts">
    import { AcIcon, AcReferencedTooltip } from "ACUi/index";

    export let description: string | null = null;
    export let label: string;
    export let extraClass: string = '';
    export let isSubComponent: boolean = false;
    export let attributes: null | Record<string, any> = null;

    const containerClass = () => {
        const base = ['acp-column-setting', 'lg:acu-flex', 'acu-px-6', 'acu-mb-2'];
        if (isSubComponent) base.push('acu-flex-col');
        if (extraClass) base.push(extraClass);
        return base.join(' ');
    };

    const labelClass = isSubComponent
        ? 'acp-column-setting__label acu-font-semibold lg:acu-pt-1 lg:acu-w-[200px]'
        : 'acp-column-setting__label acu-font-semibold lg:acu-py-2 lg:acu-w-[200px] acu-flex-shrink-0 acu-flex acu-mr-2';

    const valueClass = isSubComponent
        ? 'acp-column-setting__value acu-py-1'
        : 'acp-column-setting__value acu-flex-grow acu-py-1';
</script>

<div class={containerClass()}>
	<div class={labelClass}>
		<span class={isSubComponent ? '' : 'acu-flex-grow'}>{label}</span>
		{#if !isSubComponent && attributes && attributes['help-ref']}
			<AcReferencedTooltip reference={attributes['help-ref']} position="right" closeDelay={300}>
				<span class="acu-cursor-pointer"><AcIcon icon="question" size="sm" /></span>
			</AcReferencedTooltip>
		{/if}
	</div>

	<div class={valueClass}>
		<slot />
		{#if description}
			<small class="acp-column-setting__description acu-block acu-py-1 acu-text-[#888] acu-text-[12px]">
				{@html description}
			</small>
		{/if}
	</div>
</div>