<script lang="ts">
    import AcPanelBody from "ACUi/acui-panel/AcPanelBody.svelte";
    import AcPanelHeader from "ACUi/acui-panel/AcPanelHeader.svelte";

    export let shadow: boolean = true;
    export let border: boolean = true;
    export let rounded: boolean = true;
    export let title: string | undefined = '';
    export let classNames: string[] = [];

    let classes = ['acu-bg-[white]', 'acu-border', 'acu-border-solid', 'acu-border-ui-border', 'acu-mb-[15px]'];

    if (shadow) {
        classes.push('acu-shadow');
    }

    if (rounded) {
        classes.push('acu-rounded-[10px]');
    }

    if (border) {
        classes.push('acu-border acu-border-solid acu-border-ui-border');
    }

    classes.push(...classNames);
</script>
<div class="{classes.join(' ')}">
	{#if title }
		<AcPanelHeader title={title}/>
	{/if}
	<slot name="header"></slot>

	{#if $$slots.body}
		<slot name="body"></slot>
	{:else}
		<AcPanelBody>
			<slot name="body"></slot>
		</AcPanelBody>
	{/if}
	<slot name="footer"></slot>
</div>