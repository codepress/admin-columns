<script lang="ts">
    import AcInputGroupText from "./AcInputGroupText.svelte";


    export let before: string = '';
    export let after: string = '';
    export let containerClasses: string = '';

</script>
<div class="acui-input-group {containerClasses}">
	{#if before.length > 0}
		<AcInputGroupText>{before}</AcInputGroupText>
	{/if}
	<slot name="before"></slot>
	<slot></slot>
	<slot name="after"></slot>
	{#if after.length > 0}
		<AcInputGroupText>{after}</AcInputGroupText>
	{/if}
</div>