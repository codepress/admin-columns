<script lang="ts">

    export let title: string
    export let subtitle: string | null = null
</script>
<div class="acu-pb-8 acu-mb-8 last:acu-mb-[0] last:acu-pb-[0] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border last:acu-border-b-0">
	<div class="xl:acu-flex acu-gap-8">
		<div class="xl:acu-w-[300px] lg:acu-mr-6">
			<h3 class="acu-mt-[0] acu-mb-2 acu-text-base acu-font-semibold">{title}</h3>
			{#if subtitle}
				<p>{subtitle}</p>
			{/if}
		</div>
		<div class="acu-flex-auto acu-flex-grow acu-pt-7">

			<slot></slot>

		</div>
	</div>
</div>