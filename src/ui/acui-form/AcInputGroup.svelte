<script lang="ts">
    import AcInputGroupText from "./AcInputGroupText.svelte";


    export let before: string|null = '';
    export let after: string|null = '';
    export let containerClasses: string = '';

</script>
<div class="acui-input-group {containerClasses}">
	{#if before && before.length > 0}
		<AcInputGroupText>{before}</AcInputGroupText>
	{/if}
	<slot name="before"></slot>
	<slot></slot>
	<slot name="after"></slot>
	{#if after && after.length > 0}
		<AcInputGroupText>{after}</AcInputGroupText>
	{/if}
</div>