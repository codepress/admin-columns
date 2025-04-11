<script lang="ts">
    export let width: string = '';
    export let right: boolean = false;
    export let colspan: number|string = 1;
    export let classList: string[] = [];

    export let density: 'default'|'compact' = 'default';

    let classes = [
        'acu-p-1',
        'acu-px-3',
        ...classList
    ]

    if( density === 'default' ){
        classes.push('acu-py-3')
    }

    if( density === 'compact' ){
        classes.push('acu-py-1')
    }


</script>

<td class="{ classes.join(' ')}" style:width={width} class:acu-text-right={right} colspan={parseInt(colspan.toString())}>
	<slot></slot>
</td>