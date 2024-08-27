<script lang="ts">
	export let width: string = '';
    export let right: boolean = false;
</script>

<td class="acu-p-1 acu-px-3 acu-py-1.5" style:width={width} class:acu-text-right={right}>
	<slot></slot>
</td>