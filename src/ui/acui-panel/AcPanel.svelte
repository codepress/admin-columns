<script lang="ts">
    import { AcPanelBody, AcPanelHeader } from "ACUi/index";

    export let shadow: boolean = true;
    export let border: boolean = true;
    export let rounded: boolean = true;
    export let title: string | undefined = '';
    export let classNames: string[] = [];
    export let containerClass: boolean = false;
    export let id: string = null;

    let classes = ['acu-bg-[white]', 'acu-border', 'acu-border-solid', 'acu-border-ui-border', 'acu-mb-[15px]'];

    if( containerClass) {
		classes.push('acu-@container/panel');
	}

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
<div class="{classes.join(' ')}" {id}>
	{#if title }
		<AcPanelHeader title={title}/>
	{/if}
	<slot name="header"></slot>

	{#if $$slots.body}
		<slot name="body"></slot>
	{:else}
		<AcPanelBody>
			<slot></slot>
		</AcPanelBody>
	{/if}
	<slot name="footer"></slot>
</div>