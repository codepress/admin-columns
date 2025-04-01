<script lang="ts">
    import AcPanelTitle from "ACUi/acui-panel/AcPanelTitle.svelte";
    import AcPanelSubtitle from "ACUi/acui-panel/AcPanelSubtitle.svelte";

    export let title: string | undefined | null = null
    export let subtitle: string | undefined | null = null
    export let type: 'h3' | 'h2' = 'h2'
    export let border: boolean = false;

    let classes = ['acu-px-[20px] acu-pt-[20px]'];

    if (border) {
        classes.push('acu-pb-[20px] acu-border-0 acu-border-b acu-border-solid acu-border-ui-border')
    }

</script>

<div class={classes.join(' ')}>
	{#if title }
		<AcPanelTitle {title} {type}/>
	{/if}
	{#if subtitle }
		<AcPanelSubtitle {subtitle} {type} />
	{/if}

	<slot></slot>
</div>