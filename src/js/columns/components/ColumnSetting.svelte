<script lang="ts">
    export let description: string|null = null;
    export let label: string;
    export let extraClass: string = '';
</script>
<style>


</style>
<div class="acp-column-setting lg:acu-flex acu-px-6 acu-mb-2 {extraClass}">

	<div class="acp-column-setting__label acu-font-semibold lg:acu-py-2 lg:acu-w-[200px]">
		{label}
	</div>

	<div class="acp-column-setting__value acu-flex-grow acu-py-1">

		<slot>

		</slot>

		{#if description}
			<small class="acp-column-setting__description acu-block acu-py-1 acu-text-[#888] acu-text-[12px]">{description}</small>
		{/if}

	</div>
</div>