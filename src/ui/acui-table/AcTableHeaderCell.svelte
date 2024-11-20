<script lang="ts">
    export let width: string = '';
    export let right: boolean = false;
    export let classList: string[] = [];
    export let density: 'default' | 'compact' = 'default';

    let classes = [
        'acu-p-1',
        'acu-px-3',
        ...classList
    ]

    if (density === 'default') {
        classes.push('acu-py-3')
    }

</script>

<th class={classes.join(' ')} style:width={width} class:acu-text-right={right} class:acu-text-left={!right}>
	<slot></slot>
</th>