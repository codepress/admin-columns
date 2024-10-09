<script lang="ts">
    export let description: string | null = null;
    export let label: string;
    export let extraClass: string = '';
    export let isSubComponent: boolean = false;
</script>
<style>


</style>

{#if isSubComponent}
	<div class="acp-column-setting lg:acu-flex acu-flex-col acu-px-6 acu-mb-2 {extraClass}">

		<div class="acp-column-setting__label acu-font-semibold lg:acu-pt-1 lg:acu-w-[200px]">
			{label}
		</div>

		<div class="acp-column-setting__value acu-py-1">

			<slot>

			</slot>

			{#if description}
				<small class="acp-column-setting__description acu-block acu-py-1 acu-text-[#888] acu-text-[12px]">{@html description}</small>
			{/if}

		</div>
	</div>
{:else}
	<div class="acp-column-setting lg:acu-flex acu-px-6 acu-mb-2 {extraClass}">

		<div class="acp-column-setting__label acu-font-semibold lg:acu-py-2 lg:acu-w-[200px] acu-flex-shrink-0">
			{label}
		</div>

		<div class="acp-column-setting__value acu-flex-grow acu-py-1">

			<slot>

			</slot>

			{#if description}
				<small class="acp-column-setting__description acu-block acu-py-1 acu-text-[#888] acu-text-[12px]">{@html description}</small>
			{/if}

		</div>
	</div>
{/if}