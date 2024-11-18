<script lang="ts">
	export let width: string = '';
    export let right: boolean = false;
</script>

<th class="acu-p-1 acu-px-3 acu-py-3" style:width={width} class:acu-text-right={right} class:acu-text-left={!right}>
	<slot></slot>
</th>