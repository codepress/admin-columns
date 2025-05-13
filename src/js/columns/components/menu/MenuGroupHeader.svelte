<script lang="ts">
    export let indicator: boolean = true;
    export let opened: boolean = true;

    $: openedClasses = !opened? 'acu-transform acu-rotate-180' : '';
</script>
<button
	on:click
	class="acu-bg-[transparent] acu-border-none acu-relative acu-font-bold acu-block acu-w-full acu-text-left acu-rounded-[5px] acu-cursor-pointer acu-p-[5px] hover:acu-bg-[white] rtl:acu-text-right"
>
	<slot></slot>
	{#if indicator}
		<span
			class="dashicons dashicons-arrow-up-alt2
				acu-absolute acu-right-[10px] acu-top-[50%] rtl:acu-hidden
				acu-text-[16px] acu-text-[#888] acu-w-[16px] acu-h-[16px] acu-mt-[-8px] {openedClasses}">
		</span>
	{/if}
</button>