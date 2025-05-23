<script lang="ts">
	export let label: string;
	export let description: string|null;
</script>

<div class="ac-listscreen-prefs-section acu-pb-8 acu-mb-8 last:acu-mb-[0] last:acu-pb-[0] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border last:acu-border-b-0">
	<div class="ac-listscreen-prefs-setting xl:acu-flex">
		<div class="ac-listscreen-prefs-setting__label xl:acu-w-[240px] lg:acu-mr-6 rtl:acu-mr-[0] rtl:acu-ml-6">
			<h3 class="acu-mt-[0] acu-mb-2 acu-text-base acu-font-semibold">{label}</h3>
			{#if description}
				<p>{description}</p>
			{/if}
		</div>
		<div class="ac-listscreen-prefs-setting__body acu-flex-auto acu-flex-grow">
			<slot></slot>
		</div>
	</div>
</div>