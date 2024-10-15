<script lang="ts">
    export let shadow: boolean = true;
    export let border: boolean = true;
    export let rounded: boolean = true;
    export let title: string | undefined = '';
    export let classNames: string[] = [];

    let classes = ['acu-bg-[white]', 'acu-p-[20px]', 'acu-border', 'acu-border-solid', 'acu-border-ui-border', 'acu-mb-[15px]'];

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
		<h3 class="acu-mt-[0]">{title}</h3>
	{/if}
	<slot></slot>
</div>