<script lang="ts">
    export let title: string | undefined | null = null
    export let subtitle: string | undefined | null = null
    export let type: 'h3' | 'h2' = 'h3'
    export let border: boolean = false;

    let classes = ['acu-px-[20px] acu-pt-[20px]'];

    if (border) {
        classes.push('acu-pb-[20px] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border')
    } else {

    }

</script>

<div class={classes.join(' ')}>
	{#if title }
		{#if type === 'h2' }
			<h2 class="acu-my-[0] acu-text-2xl acu-font-normal">{title}</h2>
		{:else}
			<h3 class="acu-my-[0]">{title}</h3>
		{/if}
	{/if}
	{#if subtitle }
		<span>{subtitle}</span>
	{/if}

	<slot></slot>
</div>